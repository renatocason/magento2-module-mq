<?php

namespace Rcason\Mq\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rcason\Mq\Api\Config\ConfigInterface as QueueConfig;
use Magento\Framework\App\State;

class QueueListCommand extends Command
{
    const COMMAND_QUEUE_LIST = 'ce_mq:queues:list';

    /**
     * @var QueueConfig
     */
    private $queueConfig;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @param State $state
     * @param QueueConfig $queueConfig
     * @param string|null $name
     */
    public function __construct(
        State $state,
        QueueConfig $queueConfig,
        $name = null
    ) {
        $this->state = $state;
        $this->queueConfig = $queueConfig;

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

        $queueNames = $this->queueConfig->getQueueNames();
        if(count($queueNames) == 0) {
            $output->writeln('No configured queue.');
            return;
        }

        // Print header
        $rowFormat = '%-20s %-20s %s';
        $output->writeln(sprintf(
            $rowFormat,
            'Queue Name',
            'Broker',
            'Consumer'
        ));

        // Print queue rows
        foreach($queueNames as $name) {
            $output->writeln(sprintf(
                $rowFormat,
                $name,
                $this->queueConfig->getQueueBroker($name),
                get_class($this->queueConfig->getQueueConsumerInstance($name))
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_QUEUE_LIST);
        $this->setDescription('List of defined queues');

        parent::configure();
    }
}
