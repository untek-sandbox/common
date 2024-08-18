<?php

namespace Untek\FrameworkPlugin\MessengerRdKafkaTransport\Infrastructure\Messenger\Symfony\Transport;

use RdKafka\Conf;
use RdKafka\Producer;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Untek\Framework\Messenger\Infrastructure\Messenger\Symfony\Stamp\TopicStamp;

class RdKafkaSender implements SenderInterface
{
    private string $topic;

    private string $broker;

    private string $clientId;

    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    public function setTopic(string $topic): void
    {
        $this->topic = $topic;
    }

    public function setBroker(string $broker): void
    {
        $this->broker = $broker;
    }

    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function send(Envelope $envelope): Envelope
    {
        $conf = new Conf();
        $conf->set('enable.idempotence', 'true');
        $conf->set('bootstrap.servers', $this->broker);
        $conf->set('metadata.broker.list', $this->broker);
        $producer = new Producer($conf);
        $producer->addBrokers($this->broker);

        $envelope = $this->addStamps($envelope);
        $encodedEnvelope = $this->serializer->encode($envelope);
        /** @var TransportMessageIdStamp $transportMessageIdStamp */
        $transportMessageIdStamp = $envelope->last(TransportMessageIdStamp::class);

        $topic = $producer->newTopic($this->topic);
        $topic->produce(
            RD_KAFKA_PARTITION_UA,
            0,
            $encodedEnvelope['body'],
            $transportMessageIdStamp->getId()
        );
        $producer->poll(0);
        $producer->flush(1000);

        return $envelope;
    }

    protected function addStamps(Envelope $envelope): Envelope
    {
        $id = uniqid();
        $envelope = $envelope->with(new TransportMessageIdStamp($id));
        /** @var TopicStamp $topicStamp */
        $topicStamp = $envelope->last(TopicStamp::class);
        if (empty($topicStamp)) {
            $envelope = $envelope->with(new TopicStamp($this->topic));
        }
        return $envelope;
    }
}
