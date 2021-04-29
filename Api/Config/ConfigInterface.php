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
     * @return \Rcason\Mq\Api\BrokerInterface
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
     * @return \Rcason\Mq\Api\BrokerInterface
     */
    public function getQueueBrokerInstance($name);
    
    /**
     * Return an instance of the queue consumer
     * 
     * @return \Rcason\Mq\Api\ConsumerInterface
     */
    public function getQueueConsumerInstance($name);
    
    /**
     * Return an the queue message schema
     * 
     * @return mixed
     */
    public function getQueueMessageSchema($name);

    /**
     * Return polling interval in ms
     *
     * @return int
     */
    public function getQueuePollInterval($name);

    /**
     * Return maximum number of messages to process
     *
     * @return int
     */
    public function getQueueLimit($name);

    /**
     * Return requeue messages on failure
     *
     * @return bool
     */
    public function getQueueRequeue($name);

    /**
     * Return stop process when queue is empty
     *
     * @return bool
     */
    public function getQueueRunOnce($name);

    /**
     * Return minimum number of seconds before a failed job retries. Not supported by amqp
     *
     * @return int
     */
    public function getQueueRetryInterval($name);

    /**
     * Return maximum number of attempts before the system stops retrying. Not supported by amqp
     *
     * @return int
     */
    public function getQueueMaxRetries($name);
}
