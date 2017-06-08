<?php

namespace Rcason\Mq\Model\Config;

class SchemaLocator implements \Magento\Framework\Config\SchemaLocatorInterface
{
    /**
     * XML schema for config file.
     */
    const CONFIG_FILE_SCHEMA = 'ce_queue.xsd';

    /**
     * @var string
     */
    protected $schema = null;

    /**
     * @var string
     */
    protected $perFileSchema = null;

    /**
     * @param \Magento\Framework\Config\Dom\UrnResolver $urnResolver
     */
    public function __construct(\Magento\Framework\Config\Dom\UrnResolver $urnResolver)
    {
        $this->schema = $urnResolver->getRealPath('urn:magento:module:Rcason_Mq:etc/ce_mq.xsd');
        $this->perFileSchema = $urnResolver->getRealPath('urn:magento:module:Rcason_Mq:etc/ce_mq.xsd');
    }

    /**
     * {@inheritdoc}
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * {@inheritdoc}
     */
    public function getPerFileSchema()
    {
        return $this->perFileSchema;
    }
}
