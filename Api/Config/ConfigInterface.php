<?php

namespace Rcason\Mq\Api\Config;

/**
 * @api
 */
interface ConfigInterface
{
    /**
     * Return the list of configured brokers
     * 
     * @return string[]
     */
    public function getBrokerNames();
    
    /**
     * Return an instance of the given broker
     * 
     * @return \Rcason\Mq\Api\BrokerInstance
     */
    public function getBrokerInstance($name);
    
    /**
     * Return the list of configured queues
     * 
     * @return string[]
     */
    public function getQueueNames();
    
    /**
     * Return the queue broker code
     * 
     * @return string
     */
    public function getQueueBroker($name);
    
    /**
     * Return an instance of the queue broker
     * 
     * @return \Rcason\Mq\Api\BrokerInstance
     */
    public function getQueueBrokerInstance($name);
    
    /**
     * Return an instance of the queue consumer
     * 
     * @return \Rcason\Mq\Api\ConsumerInstance
     */
    public function getQueueConsumerInstance($name);
    
    /**
     * Return an the queue message schema
     * 
     * @return mixed
     */
    public function getQueueMessageSchema($name);
}
