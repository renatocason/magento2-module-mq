<?php

namespace Rcason\Mq\Model;

use Rcason\Mq\Api\Data\MessageEnvelopeInterfaceFactory;
use Rcason\Mq\Api\Config\ConfigInterface as QueueConfig;
use Rcason\Mq\Api\MessageEncoderInterface;

class Publisher implements \Rcason\Mq\Api\PublisherInterface
{
    /**
     * @var MessageEnvelopeInterfaceFactory
     */
    private $messageEnvelopeFactory;
    
    /**
     * @var MessageEncoderInterface
     */
    private $messageEncoder;
    
    /**
     * @var QueueConfig
     */
    private $queueConfig;

    /**
     * @param MessageEnvelopeInterfaceFactory $messageEnvelopeFactory
     * @param MessageEncoderInterface $messageEncoder
     * @param QueueConfig $queueConfig
     */
    public function __construct(
        MessageEnvelopeInterfaceFactory $messageEnvelopeFactory,
        MessageEncoderInterface $messageEncoder,
        QueueConfig $queueConfig
    ) {
        $this->messageEnvelopeFactory = $messageEnvelopeFactory;
        $this->messageEncoder = $messageEncoder;
        $this->queueConfig = $queueConfig;
    }
    
    /**
     * {@inheritdoc}
     */
    public function publish($queueName, $messageContent)
    {
        $envelope = $this->messageEnvelopeFactory->create()
            ->setContentType($this->messageEncoder->getContentType())
            ->setContent(
                $this->messageEncoder->encode($queueName, $messageContent)
            );
        
        $this->queueConfig->getQueueBrokerInstance($queueName)
            ->enqueue($envelope);
    }
}
