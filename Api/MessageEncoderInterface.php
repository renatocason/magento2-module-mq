<?php

namespace Rcason\Mq\Api;

/**
 * @api
 */
interface MessageEncoderInterface
{
    /**
     * Return encoding content type
     *
     * @return string
     */
    public function getContentType();
    
    /**
     * Encode message based on queue configuration.
     *
     * @param string $queueName
     * @param mixed $message
     * @return string
     * @throws LocalizedException
     */
    public function encode($queueName, $message);

    /**
     * Decode message based on queue configuration.
     *
     * @param string $queueName
     * @param string $message
     * 
     * @return mixed
     */
    public function decode($queueName, $message);
}
