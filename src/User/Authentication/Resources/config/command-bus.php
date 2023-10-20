<?php

use Untek\Model\Cqrs\CommandBusConfiguratorInterface;
use Untek\User\Authentication\Application\Commands\GenerateTokenByPasswordCommand;
use Untek\User\Authentication\Application\Handlers\GenerateTokenByPasswordCommandHandler;

return function (CommandBusConfiguratorInterface $configurator) {
    $configurator->define(GenerateTokenByPasswordCommand::class, GenerateTokenByPasswordCommandHandler::class);
};
