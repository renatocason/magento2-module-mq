<?php

namespace Rcason\Mq\Model\Config;

class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($source)
    {
        return [
            'brokers' => $this->toConfigArray($source, 'ceBroker'),
            'queues' => $this->toConfigArray($source, 'ceQueue'),
        ];
    }
    
    /**
     * Return config node converted to array
     */
    protected function toConfigArray($source, $nodeName)
    {
        $items = [];
        $config = $source->getElementsByTagName($nodeName);
        
        foreach($config as $configNode) {
            $item = [];
            foreach($configNode->attributes as $attribute) {
                $item[$attribute->name] = $attribute->value;
            }
            $items []= $item;
        }

        return $items;
    }
}
