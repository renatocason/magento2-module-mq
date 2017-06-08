# Magento 2 Message Queue Module
Lightweight implementation of message queue for Magento 2 Community Edition.

## System requirements
This extension supports the following versions of Magento:

*	Community Edition (CE) versions 2.1.x

## Installation
1. Require the module via Composer
```bash
$ composer require renatocason/magento2-module-mq
```

2. Enable the module
```bash
$ bin/magento module:enable Rcason_Mq
$ bin/magento setup:upgrade
```

3. Install a message queue backend extension of your choice (see _Message queue backends_ section)
4. Configure your queue(s) and implement your consumer(s) (see _Implementation_ section)
5. Check the correct configuration of your queue(s)
```bash
$ bin/magento ce_mq:queues:list
```
6. Run your consumer(s)
```bash
$ bin/magento ce_mq:consumers:start product.updates
```

## Message queue backends
This module does not include any message queue backend implementation.
You will have to chose and install one of the following modules (or implement your own) in order to get your message queues working:
* [MqMysql](https://github.com/renatocason/magento2-module-mq-mysql) - Stores messages in the database
* [MqAmqp](https://github.com/renatocason/magento2-module-mq-amqp) - Integrates any AMQP queue manager (i.e. RabbitMQ)
* Amazon SQS - Integrates Amazon SQS as a queue manager (work in progress)

## Implementation
A simple example can be found [here](https://github.com/renatocason/magento2-module-mq-example).

1. Configure queue(s) in your module's _etc/m2_mq.xml_ file
```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Rcason_Mq:etc/ce_mq.xsd">
    <ceQueue name="product.updates" broker="mysql"
        messageSchema="int"
        consumerInterface="Rcason\MqExample\Model\ExampleConsumer"/>
</config>
```
2. Require the publisher in the classes you need it
```php
/**
 * @param \Rcason\Mq\Api\PublisherInterface $publisher
 */
public function __construct(
    \Rcason\Mq\Api\PublisherInterface $publisher
) {
    $this->publisher = $publisher;
}
```
3. Use it to queue messages
```php
$this->publisher->publish('product.updates', $productId);
```
4. Implement your consumer(s)
```php
class ExampleConsumer implements \Rcason\Mq\Api\ConsumerInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($productId)
    {
        // Your code here
    }
}
```

## Authors, contributors and maintainers

Author:
- [Renato Cason](https://github.com/renatocason)

## License
Licensed under the Open Software License version 3.0
