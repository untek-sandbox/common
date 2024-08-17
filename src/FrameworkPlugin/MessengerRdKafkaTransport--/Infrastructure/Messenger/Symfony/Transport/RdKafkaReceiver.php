<?php

namespace Untek\FrameworkPlugin\MessengerRdKafkaTransport\Infrastructure\Messenger\Symfony\Transport;

use Untek\FrameworkPlugin\MessengerRdKafkaTransport\Infrastructure\Messenger\Symfony\Stamp\ConsumeMessageStampRd;
use Mservis\Operator\Shared\Infrastructure\Runtime;
use RdKafka\Conf;
use RdKafka\Consumer;
use RdKafka\ConsumerTopic;
use RdKafka\Message;
use RdKafka\TopicConf;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Messenger\Transport\Receiver\QueueReceiverInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Untek\Framework\Messenger\Infrastructure\Messenger\Symfony\Stamp\TopicStamp;

class RdKafkaReceiver implements QueueReceiverInterface
{
    private ConsumerTopic $topicReceiver;

    public function __construct(
        protected SerializerInterface $serializer,
        protected string $topic
    ) {
    }

    protected function createTopicReceiver(string $groupId, string $topicName, int $partitionId): ConsumerTopic
    {
        $conf = $this->createConfig($groupId, 'instance-partition-' . $partitionId);
        $consumer = new Consumer($conf);

        $topicConf = new TopicConf();
        $topicConf->set('auto.commit.interval.ms', '100');
        $topicConf->set('offset.store.method', 'broker');
        $topicConf->set('auto.offset.reset', 'earliest');

        $topic = $consumer->newTopic($topicName, $topicConf);

        $topic->consumeStart($partitionId, RD_KAFKA_OFFSET_STORED);
        return $topic;
    }

    protected function createConfig(string $groupId, string $instanceId): Conf
    {
        $conf = new Conf();
        $conf->set('group.id', $groupId);
        $conf->set('group.instance.id', $instanceId);
        $conf->set('bootstrap.servers', getenv('KAFKA_BROKER'));
        $conf->set('metadata.broker.list', getenv('KAFKA_BROKER'));
        return $conf;
    }

    public function get(): iterable
    {
        return $this->getFromQueues(['default']);
    }

    public function getFromQueues(array $queueNames): iterable
    {
        $partitionId = intval($queueNames[0]);
        if (!isset($this->topicReceiver)) {
            $this->topicReceiver = $this->createTopicReceiver('group1', $this->topic, $partitionId);
        }

        /** @var Message $message */
        $message = $this->topicReceiver->consume($partitionId, 0);
        if ($message) {
            if ($message->err) {
                switch ($message->err) {
                    case RD_KAFKA_RESP_ERR_NO_ERROR:
                        // No error
                        break;
                    case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                        // No more messages; will wait for more
                        break;
                    case RD_KAFKA_RESP_ERR__TIMED_OUT:
                        // Timed out
                        break;
                    default:
                        throw new TransportException($message->errstr());
                }
            } else {
                $key = $message->key;
                $sendTimestamp = json_decode($message->payload)->timestamp ?: 0;
//                Runtime::log('Got message from queue', $sendTimestamp, $message->partition, $key);
                $envelope = $this->decodeRd($message);
                $debug = [];
                $debug['partition'] = $message->partition;
                $debug['id'] = $key;
                $debug['timestamp'] = $sendTimestamp;
                $envelope->getMessage()->setDebug($debug);
                return [$envelope];
            }
        }
        return [];
    }

    public function ack(Envelope $envelope): void
    {
    }

    protected function decodeRd(Message $consumeMessage): Envelope
    {
        $encodedEnvelope = $this->forgeEncodedEnvelopeRd($consumeMessage);
        $envelope = $this->serializer->decode($encodedEnvelope);
        $envelope = $this->addStampsFromKafkaMessageRd($envelope, $consumeMessage);
        return $envelope;
    }

    protected function addStampsFromKafkaMessageRd(Envelope $envelope, Message $consumeMessage): Envelope
    {
        $envelope = $envelope->with(new TransportMessageIdStamp($consumeMessage->key));
        $envelope = $envelope->with(new TopicStamp($consumeMessage->topic_name));
        $envelope = $envelope->with(new ConsumeMessageStampRd($consumeMessage));
        return $envelope;
    }

    protected function forgeEncodedEnvelopeRd(Message $message): array
    {
        $headers = $message->headers;
        return [
            'body' => $message->payload,
            'headers' => $headers,
        ];
    }

    public function reject(Envelope $envelope): void
    {
        $this->ack($envelope);
    }
}