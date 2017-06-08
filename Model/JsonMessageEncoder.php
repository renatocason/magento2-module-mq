<?php

namespace Rcason\Mq\Model;

use Rcason\Mq\Api\Config\ConfigInterface as QueueConfig;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\Webapi\ServicePayloadConverterInterface;

class JsonMessageEncoder implements \Rcason\Mq\Api\MessageEncoderInterface
{
    const CONTENT_TYPE = 'application/json';
    
    /**
     * @var QueueConfig
     */
    private $queueConfig;

    /**
     * @var \Magento\Framework\Webapi\ServiceOutputProcessor
     */
    private $dataObjectEncoder;

    /**
     * @var \Magento\Framework\Webapi\ServiceInputProcessor
     */
    private $dataObjectDecoder;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $jsonEncoder;

    /**
     * @var \Magento\Framework\Json\DecoderInterface
     */
    private $jsonDecoder;

    /**
     * Initialize dependencies.
     *
     * @param QueueConfig $queueConfig
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Json\DecoderInterface $jsonDecoder
     * @param \Magento\Framework\Webapi\ServiceOutputProcessor $dataObjectEncoder
     * @param \Magento\Framework\Webapi\ServiceInputProcessor $dataObjectDecoder
     */
    public function __construct(
        QueueConfig $queueConfig,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder,
        \Magento\Framework\Webapi\ServiceOutputProcessor $dataObjectEncoder,
        \Magento\Framework\Webapi\ServiceInputProcessor $dataObjectDecoder
    ) {
        $this->queueConfig = $queueConfig;
        $this->dataObjectEncoder = $dataObjectEncoder;
        $this->dataObjectDecoder = $dataObjectDecoder;
        $this->jsonEncoder = $jsonEncoder;
        $this->jsonDecoder = $jsonDecoder;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType()
    {
        return self::CONTENT_TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($queueName, $message)
    {
        $schema = $this->queueConfig->getQueueMessageSchema($queueName);
        $messageObject = $this->dataObjectEncoder->convertValue($message, $schema);
        
        return $this->jsonEncoder->encode($messageObject);
    }

    /**
     * {@inheritdoc}
     */
    public function decode($queueName, $message)
    {
        $schema = $this->queueConfig->getQueueMessageSchema($queueName);
        $decodedMessage = $this->jsonDecoder->decode($message);
        
        return $this->dataObjectDecoder->convertValue($decodedMessage, $schema);
    }
}
