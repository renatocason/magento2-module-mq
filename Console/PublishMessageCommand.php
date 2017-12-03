<?php

namespace Rcason\Mq\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rcason\Mq\Api\Config\ConfigInterface as QueueConfig;
use Rcason\Mq\Api\MessageEncoderInterface;
use Rcason\Mq\Api\PublisherInterface;
use Magento\Framework\App\State;

class PublishMessageCommand extends Command
{
    const COMMAND_MESSAGES_PUBLISH = 'ce_mq:messages:publish';
    const ARGUMENT_QUEUE_NAME = 'queue';
    const ARGUMENT_MESSAGE_CONTENT = 'content';

    /**
     * @var QueueConfig
     */
    private $queueConfig;

    /**
     * @var MessageEncoderInterface
     */
    private $messageEncoder;

    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @param State $state
     * @param QueueConfig $queueConfig
     * @param MessageEncoderInterface $messageEncoder
     * @param PublisherInterface $publisher
     * @param string|null $name
     */
    public function __construct(
        State $state,
        QueueConfig $queueConfig,
        MessageEncoderInterface $messageEncoder,
        PublisherInterface $publisher,
        $name = null
    ) {
        $this->state = $state;
        $this->queueConfig = $queueConfig;
        $this->messageEncoder = $messageEncoder;
        $this->publisher = $publisher;

        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            // this tosses an error if the areacode is not set.
            $this->state->getAreaCode();
        } catch (\Exception $e) {
            $this->state->setAreaCode('adminhtml');
        }
        
        // Load and verify input arguments
        $queueName = $input->getArgument(self::ARGUMENT_QUEUE_NAME);
        $message = $input->getArgument(self::ARGUMENT_MESSAGE_CONTENT);

        // Decode message for publisher
        $message = $this->messageEncoder->decode($queueName, $message);

        // Publish message
        $this->publisher->publish(
            $queueName,
            $message
        );

        $output->writeln('Message published.');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_MESSAGES_PUBLISH);
        $this->setDescription('Publish message to queue');

        $this->addArgument(
            self::ARGUMENT_QUEUE_NAME,
            InputArgument::REQUIRED,
            'The queue name.'
        );
        $this->addArgument(
            self::ARGUMENT_MESSAGE_CONTENT,
            InputArgument::REQUIRED,
            'The json encoded content of the message.'
        );

        parent::configure();
    }
}
