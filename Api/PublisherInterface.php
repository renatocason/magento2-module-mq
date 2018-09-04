<?php

namespace Rcason\Mq\Api;

/**
 * @api
 */
interface PublisherInterface
{
    /**
     * Publish message to queue
     */
    public function publish($queueName, $messageContent);
}
