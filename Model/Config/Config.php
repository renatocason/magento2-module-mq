<?php

namespace Rcason\Mq\Model\Config;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Config\CacheInterface;

class Config extends \Magento\Framework\Config\Data
    implements \Rcason\Mq\Api\Config\ConfigInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;
    
    /**
     * @param ObjectManagerInterface $objectManager
     * @param Reader $reader
     * @param CacheInterface $cache
     * @param string $cacheId
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        Reader $reader,
        CacheInterface $cache,
        $cacheId = 'ce_mq_config'
    ) {
        $this->objectManager = $objectManager;
        
        parent::__construct($reader, $cache, $cacheId);
    }
    
    /**
     * Return the list of a specific property given a path
     */
    protected function getPropertyList($path, $prop = 'name')
    {
        return array_map(function($item) use($prop) {
            return $item[$prop];
        }, $this->get($path, []));
    }
    
    /**
     * Return a configuration item given a property value
     */
    protected function getItemByProperty($path, $value, $prop = 'name')
    {
        $items = $this->get($path, []);

        foreach($items as $item) {
            if($item[$prop] !== $value) {
                continue;
            }
            
            return $item;
        }
        
        // Throw exception if a value is not found
        throw new \Exception(sprintf(
            'Element with %s "%s" not found in %s list',
            $prop,
            $value,
            $path
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBrokerNames()
    {
        return $this->getPropertyList('brokers');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBrokerInstance($name, $queueName = null)
    {
        $config = $this->getItemByProperty('brokers', $name);
        
        return $this->objectManager->create(
            $config['implementationInstance'],
            ['queueName' => $queueName]
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getQueueNames()
    {
        return $this->getPropertyList('queues');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getQueueBroker($name)
    {
        $config = $this->getItemByProperty('queues', $name);
        
        return $config['broker'];
    }
    
    /**
     * {@inheritdoc}
     */
    public function getQueueBrokerInstance($name)
    {
        return $this->getBrokerInstance(
            $this->getQueueBroker($name),
            $name
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getQueueConsumerInstance($name)
    {
        $config = $this->getItemByProperty('queues', $name);
        
        return $this->objectManager->create(
            $config['consumerInterface']
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getQueueMessageSchema($name)
    {
        $config = $this->getItemByProperty('queues', $name);
        
        return $config['messageSchema'];
    }
}
