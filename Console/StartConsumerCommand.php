<?php

namespace Rcason\Mq\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rcason\Mq\Api\Config\ConfigInterface as QueueConfig;
use Rcason\Mq\Api\PublisherInterface;
use Rcason\Mq\Api\MessageEncoderInterface;
use Magento\Framework\App\State;

class StartConsumerCommand extends Command
{
    const COMMAND_CONSUMERS_START = 'ce_mq:consumers:start';
    const ARGUMENT_QUEUE_NAME = 'queue';
    const OPTION_POLL_INTERVAL = 'interval';
    const OPTION_MESSAGE_LIMIT = 'limit';
    const OPTION_MESSAGE_REQUEUE = 'requeue';
    const OPTION_MESSAGE_RUN_ONCE = 'run-once';
    const OPTION_MESSAGE_RETRY_INTERVAL = 'retry-interval';
    const OPTION_MESSAGE_MAX_RETRIES = 'retries';

    /**
     * @var QueueConfig
     */
    private $queueConfig;

    /**
     * @var MessageEncoderInterface
     */
    private $messageEncoder;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @param State $state
     * @param QueueConfig $queueConfig
     * @param MessageEncoderInterface $messageEncoder
     * @param string|null $name
     */
    public function __construct(
        State $state,
        QueueConfig $queueConfig,
        MessageEncoderInterface $messageEncoder,
        $name = null
    ) {
        $this->state = $state;
        $this->queueConfig = $queueConfig;
        $this->messageEncoder = $messageEncoder;

        parent::__construct($name);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->getAreaCode();
        } catch (\Exception $e) {
            $this->state->setAreaCode('adminhtml');
        }

        // Load and verify input arguments
        $queueName = $input->getArgument(self::ARGUMENT_QUEUE_NAME);

        $interval = $this->queueConfig->getQueuePollInterval($queueName);
        if ($interval === null) {
            $interval = $input->getOption(self::OPTION_POLL_INTERVAL);
        }

        $limit = $this->queueConfig->getQueueLimit($queueName);
        if ($limit === null) {
            $limit = $input->getOption(self::OPTION_MESSAGE_LIMIT);
        }

        $requeue = $this->queueConfig->getQueueRequeue($queueName);
        if ($requeue === null) {
            $requeue = (bool)$input->getOption(self::OPTION_MESSAGE_REQUEUE);
        }

        $runOnce = $this->queueConfig->getQueueRunOnce($queueName);
        if ($runOnce === null) {
            $runOnce = (bool)$input->getOption(self::OPTION_MESSAGE_RUN_ONCE);
        }

        $retryInterval = $this->queueConfig->getQueueRetryInterval($queueName);
        if ($retryInterval === null) {
            $retryInterval = $input->getOption(self::OPTION_MESSAGE_RETRY_INTERVAL);
        }

        $maxRetries = $this->queueConfig->getQueueMaxRetries($queueName);
        if ($maxRetries === null) {
            $maxRetries = $input->getOption(self::OPTION_MESSAGE_MAX_RETRIES);
        }

        // Prepare consumer and broker
        $broker = $this->queueConfig->getQueueBrokerInstance($queueName);
        $consumer = $this->queueConfig->getQueueConsumerInstance($queueName);

        do {
            $limit--;
            $message = $broker->peek();

            if (!$message) {
                usleep($interval * 1000);

                if($runOnce){
                    break;
                }
                continue;
            }

            try {
                $consumer->process(
                    $this->messageEncoder->decode($queueName, $message->getContent())
                );
                $broker->acknowledge($message);
            } catch(\Exception $ex) {
                $broker->reject($message, $requeue, $maxRetries, $retryInterval);
                $output->writeln('Error processing message: ' . $ex->getMessage());
            }
        } while($limit != 0);
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_CONSUMERS_START);
        $this->setDescription('Start queue consumer');

        $this->addArgument(
            self::ARGUMENT_QUEUE_NAME,
            InputArgument::REQUIRED,
            'The queue name.'
        );
        $this->addOption(
            self::OPTION_POLL_INTERVAL,
            null,
            InputOption::VALUE_REQUIRED,
            'Polling interval in ms (default is 200).',
            200
        );
        $this->addOption(
            self::OPTION_MESSAGE_LIMIT,
            null,
            InputOption::VALUE_REQUIRED,
            'Maximum number of messages to process (default is 0, unlimited).',
            0
        );
        $this->addOption(
            self::OPTION_MESSAGE_REQUEUE,
            null,
            InputOption::VALUE_OPTIONAL,
            'Requeue messages on failure (1 to enable, 0 to disable).',
            0
        );
        $this->addOption(
            self::OPTION_MESSAGE_RUN_ONCE,
            null,
            InputOption::VALUE_NONE,
            'Stop Process when queue is empty'
        );
        $this->addOption(
            self::OPTION_MESSAGE_RETRY_INTERVAL,
            null,
            InputOption::VALUE_OPTIONAL,
            'Minimum number of seconds before a failed job retries (default is 0 which just places the job at the end of the queue, mysql only).',
            0
        );
        $this->addOption(
            self::OPTION_MESSAGE_MAX_RETRIES,
            null,
            InputOption::VALUE_OPTIONAL,
            'The number of times the system will attempt to perform the job (default is 5, 0 means unlimited, mysql only).',
            5
        );

        parent::configure();
    }
}
