<?php

namespace Untek\Component\Cqs\Infrastructure\Services;

use Untek\Component\Cqs\Application\Interfaces\CommandBusInterface;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class CommandBus implements CommandBusInterface
{

    public function __construct(
        private MessageBusInterface                                            $messageBus,
        private \Untek\Component\Cqrs\Application\Services\CommandBusInterface $cqrsBus,
    )
    {
    }

    public function dispatch(object $command): mixed
    {
        try {
            $result = $this->messageBus->dispatch($command);
            $last = $result->last(HandledStamp::class);
            return $last?->getResult();
        } catch (NoHandlerForMessageException $exception) {
            return $this->cqrsBus->handle($command);
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
