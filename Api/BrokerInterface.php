<?php

namespace Rcason\Mq\Api;

use Rcason\Mq\Api\Data\MessageEnvelopeInterface;

interface BrokerInterface
{
    /**
     * Add message to queue
     * 
     * @return void
     */
    public function enqueue(MessageEnvelopeInterface $message);
    
    /**
     * Get next message in the queue
     * 
     * @return \Rcason\Mq\Api\Data\MessageEnvelopeInterface|null
     */
    public function peek();
    
    /**
     * Mark message as processed
     * 
     * @return void
     */
    public function acknowledge(MessageEnvelopeInterface $message);
    
    /**
     * Reject message
     * 
     * @return void
     */
    public function reject(MessageEnvelopeInterface $message, bool $requeue);
}
