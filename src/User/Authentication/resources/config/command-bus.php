<?php

use Untek\Model\Cqrs\Application\Services\CommandBusConfiguratorInterface;
use Untek\User\Authentication\Application\Commands\GenerateTokenByPasswordCommand;
use Untek\User\Authentication\Application\Handlers\GenerateTokenByPasswordCommandHandler;

//\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

return function (CommandBusConfiguratorInterface $configurator) {
    $configurator->define(GenerateTokenByPasswordCommand::class, GenerateTokenByPasswordCommandHandler::class);
};
