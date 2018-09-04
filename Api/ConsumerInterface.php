<?php

namespace Rcason\Mq\Api;

/**
 * @api
 */
interface ConsumerInterface
{
    /**
     * Remove message from queue
     * 
     * @param mixed $message The decoded message content
     */
    public function process($message);
}
