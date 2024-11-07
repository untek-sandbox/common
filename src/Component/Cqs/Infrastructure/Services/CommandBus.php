<?php

namespace Untek\Component\Cqs\Infrastructure\Services;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Untek\Component\Cqs\Application\Interfaces\CommandBusInterface;

class CommandBus implements CommandBusInterface
{

    public function __construct(
        private MessageBusInterface $messageBus,
    )
    {
    }

    public function dispatch(object $command): mixed
    {
        try {
            $result = $this->messageBus->dispatch($command);
            $last = $result->last(HandledStamp::class);
            return $last?->getResult();
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious();
        }
    }

    /**
     * @param object $command
     * @return mixed
     * @deprecated
     * @see self::dispatch()
     */
    public function handle(object $command): mixed
    {
        return $this->dispatch($command);
    }
}
