<?php

namespace Rcason\Mq\Api\Data;

/**
 * @api
 */
interface MessageEnvelopeInterface
{
    const BROKER_REF_ID = 'broker_ref_id';
    const CONTENT_TYPE = 'content_type';
    const CONTENT = 'content';
    
    /**
     * Gets the broker ref. id.
     *
     * @return string
     */
    public function getBrokerRefId();

    /**
     * Sets the broker ref. id.
     *
     * @param string $refId
     * @return $this
     */
    public function setBrokerRefId($refId);
    
    /**
     * Gets the message content type.
     *
     * @return string
     */
    public function getContentType();

    /**
     * Sets the message content type.
     *
     * @param string $contentType
     * @return $this
     */
    public function setContentType($contentType);
    
    /**
     * Gets the message content.
     *
     * @return string
     */
    public function getContent();

    /**
     * Sets the message content.
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content);
}
