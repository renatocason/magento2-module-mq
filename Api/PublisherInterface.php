<?php

namespace Rcason\Mq\Api;

interface PublisherInterface
{
    /**
     * Publish message to queue
     */
    public function publish($queueName, $messageContent);
}
