<?php

namespace Rcason\Mq\Model;

use Rcason\Mq\Api\Data\MessageEnvelopeInterface;

class MessageEnvelope extends \Magento\Framework\DataObject
    implements MessageEnvelopeInterface
{
    /**
     * @inheritdoc
     */
    public function getBrokerRefId()
    {
        return $this->getData(MessageEnvelopeInterface::BROKER_REF_ID);
    }

    /**
     * @inheritdoc
     */
    public function setBrokerRefId($refId)
    {
        $this->setData(MessageEnvelopeInterface::BROKER_REF_ID, $refId);
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function getContentType()
    {
        return $this->getData(MessageEnvelopeInterface::CONTENT_TYPE);
    }

    /**
     * @inheritdoc
     */
    public function setContentType($contentType)
    {
        $this->setData(MessageEnvelopeInterface::CONTENT_TYPE, $contentType);
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function getContent()
    {
        return $this->getData(MessageEnvelopeInterface::CONTENT);
    }

    /**
     * @inheritdoc
     */
    public function setContent($content)
    {
        $this->setData(MessageEnvelopeInterface::CONTENT, $content);
        return $this;
    }
}
