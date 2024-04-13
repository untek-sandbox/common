<?php

namespace Untek\FrameworkPlugin\MessengerRdKafkaTransport\Infrastructure\Messenger\Factories;

use Forecast\Map\Modules\Mq\Infrastructure\Messenger\Interfaces\MessengerTransportFactoryInterface;
use Untek\FrameworkPlugin\MessengerRdKafkaTransport\Infrastructure\Messenger\Symfony\Transport\RdKafkaReceiver;
use Untek\FrameworkPlugin\MessengerRdKafkaTransport\Infrastructure\Messenger\Symfony\Transport\RdKafkaSender;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class RdKafkaTransportFactory implements MessengerTransportFactoryInterface
{
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    public function createSender(string $topic): SenderInterface
    {
        $sender = $this->container->get(RdKafkaSender::class);
        $sender->setBroker(getenv('KAFKA_BROKER'));
        $sender->setClientId(getenv('KAFKA_CLIENT_ID'));
        $sender->setTopic($topic);
        return $sender;
    }

    public function createReceiver(string $topic): ReceiverInterface
    {
        $serializer = $this->container->get(SerializerInterface::class);
        return new RdKafkaReceiver($serializer, $topic);
    }
}
