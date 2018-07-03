<?php

namespace Rcason\Mq\Test\Unit\Console;

use Rcason\Mq\Console\PublishMessageCommand;

class PublishMessageCommandTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager */
    private $objectManager;

    /**
     * @var PublishMessageCommand
     */
    private $command;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        parent::setUp();
    }

    /**
     * Test configure() method implicitly via construct invocation.
     *
     * @return void
     */
    public function testConfigure()
    {
        $this->command = $this->objectManager->getObject('Rcason\Mq\Console\PublishMessageCommand');

        $this->assertEquals(PublishMessageCommand::COMMAND_MESSAGES_PUBLISH, $this->command->getName());
        $this->assertEquals('Publish message to queue', $this->command->getDescription());
        
        /** Test arguments and options definitions */
        $this->command->getDefinition()->getArgument(PublishMessageCommand::ARGUMENT_QUEUE_NAME);
        $this->command->getDefinition()->getArgument(PublishMessageCommand::ARGUMENT_MESSAGE_CONTENT);
    }
}
