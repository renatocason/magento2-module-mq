<?php

namespace Rcason\Mq\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rcason\Mq\Api\Config\ConfigInterface as QueueConfig;
use Magento\Framework\App\State;

class BrokerListCommand extends Command
{
    const COMMAND_BROKER_LIST = 'ce_mq:brokers:list';

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
