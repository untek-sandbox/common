<?php

namespace Untek\FrameworkPlugin\MessengerRdKafkaTransport\Infrastructure\Messenger\Symfony\Stamp;

use RdKafka\Message;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class ConsumeMessageStampRd implements StampInterface
{
    private Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
