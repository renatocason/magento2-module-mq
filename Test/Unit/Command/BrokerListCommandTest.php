<?php

namespace Rcason\Mq\Test\Unit\Console;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Rcason\Mq\Console\BrokerListCommand;

class BrokerListCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var BrokerListCommand
     */
    private $command;

    /**
     * {@inheritdoc}
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
        $this->command = $this->objectManager->getObject('Rcason\Mq\Console\BrokerListCommand');

        $this->assertEquals(BrokerListCommand::COMMAND_BROKER_LIST, $this->command->getName());
        $this->assertEquals('List of defined brokers', $this->command->getDescription());
    }
}
