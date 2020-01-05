<?php

namespace Rcason\Mq\Test\Unit\Console;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Rcason\Mq\Console\StartConsumerCommand;

class StartConsumerCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var StartConsumerCommand
     */
    private $command;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        parent::setUp();
    }

    /**
     * Test configure() method implicitly via construct invocation.
     *
     * @return void
     */
    public function testConfigure()
    {
        $this->command = $this->objectManager->getObject('Rcason\Mq\Console\StartConsumerCommand');

        $this->assertEquals(StartConsumerCommand::COMMAND_CONSUMERS_START, $this->command->getName());
        $this->assertEquals('Start queue consumer', $this->command->getDescription());
        
        /** Test arguments and options definitions */
        $this->command->getDefinition()->getArgument(StartConsumerCommand::ARGUMENT_QUEUE_NAME);
        $this->command->getDefinition()->getOption(StartConsumerCommand::OPTION_POLL_INTERVAL);
        $this->command->getDefinition()->getOption(StartConsumerCommand::OPTION_MESSAGE_LIMIT);
        $this->command->getDefinition()->getOption(StartConsumerCommand::OPTION_MESSAGE_REQUEUE);
    }
}
