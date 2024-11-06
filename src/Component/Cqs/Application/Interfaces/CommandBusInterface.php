<?php

namespace Untek\Component\Cqs\Application\Interfaces;

interface CommandBusInterface
{

    public function dispatch(object $command): mixed;

    /**
     * @param object $command
     * @return mixed
     * @deprecated
     * @see self::dispatch()
     */
    public function handle(object $command): mixed;
}