<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Framework\Socket\Infrastructure\Services\SocketDaemon;
use Untek\Framework\Socket\Infrastructure\Storage\ConnectionRamStorage;
use Untek\Framework\Socket\Presentation\Cli\Commands\SocketCommand;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\Framework\Socket\Presentation\Cli\Commands\SendMessageToSocketCommand;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(ConnectionRamStorage::class, ConnectionRamStorage::class);
    $services->set(SocketDaemon::class, SocketDaemon::class)
        ->args([
            service(ConnectionRamStorage::class),
            service(TokenServiceInterface::class),
            getenv('WEB_SOCKET_LOCAL_URL'),
            getenv('WEB_SOCKET_CLIENT_URL'),
        ]);
    $services->set(SocketCommand::class, SocketCommand::class)
        ->args([
            service(SocketDaemon::class),
        ]);
    $services->set(SendMessageToSocketCommand::class, SendMessageToSocketCommand::class)
        ->args([
            service(SocketDaemon::class),
        ]);
};