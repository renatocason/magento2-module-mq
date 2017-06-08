<?php

namespace Rcason\Mq\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rcason\Mq\Api\Config\ConfigInterface as QueueConfig;

class BrokerListCommand extends Command
{
    const COMMAND_BROKER_LIST = 'ce_mq:brokers:list';

    /**
     * @var QueueConfig
     */
    private $queueConfig;

    /**
     * @param QueueConfig $queueConfig
     * @param string|null $name
     */
    public function __construct(QueueConfig $queueConfig, $name = null)
    {
        $this->queueConfig = $queueConfig;
        
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $brokerNames = $this->queueConfig->getBrokerNames();
        if(count($brokerNames) == 0) {
            $output->writeln('No configured brokers.');
            return;
        }
        
        // Print header
        $rowFormat = '%-20s %s';
        $output->writeln(sprintf(
            $rowFormat,
            'Broker Name',
            'Class'
        ));
        
        // Print broker rows
        foreach($brokerNames as $name) {
            $output->writeln(sprintf(
                $rowFormat,
                $name,
                get_class($this->queueConfig->getBrokerInstance($name))
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_BROKER_LIST);
        $this->setDescription('List of defined brokers');
        
        parent::configure();
    }
}
