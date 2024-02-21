<?php

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Framework\Socket\Domain\Interfaces\Services\ClientMessageHandlerInterface;
use Untek\Framework\Socket\Infrastructure\Services\ClientMessageHandler;
use Untek\Framework\Socket\Infrastructure\Services\SocketDaemon;
use Untek\Framework\Socket\Infrastructure\Storage\ConnectionRamStorage;
use Untek\Framework\Socket\Presentation\Cli\Commands\SocketCommand;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\Framework\Socket\Presentation\Cli\Commands\SendMessageToSocketCommand;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    try {
        $services->get(ClientMessageHandlerInterface::class);
    } catch (ServiceNotFoundException $e) {
        $services->set(ClientMessageHandlerInterface::class, ClientMessageHandler::class);
    }
    
    $services->set(ConnectionRamStorage::class, ConnectionRamStorage::class);

    if(getenv('APP_MODE') === 'test') {
        $services->set(SocketDaemon::class, \Untek\Framework\Socket\Infrastructure\Services\SocketDaemonTest::class)
            ->args([
                service(ConnectionRamStorage::class),
                service(TokenServiceInterface::class),
                service(ClientMessageHandlerInterface::class),
                getenv('WEB_SOCKET_LOCAL_URL'),
                getenv('WEB_SOCKET_CLIENT_URL'),
                getenv('APP_ENV'),
            ]);
    } else {
        $services->set(SocketDaemon::class, SocketDaemon::class)
            ->args([
                service(ConnectionRamStorage::class),
                service(TokenServiceInterface::class),
                service(ClientMessageHandlerInterface::class),
                getenv('WEB_SOCKET_LOCAL_URL'),
                getenv('WEB_SOCKET_CLIENT_URL'),
                getenv('APP_ENV'),
            ]);
    }
    
    $services->set(SocketCommand::class, SocketCommand::class)
        ->args([
            service(SocketDaemon::class),
        ]);
    $services->set(SendMessageToSocketCommand::class, SendMessageToSocketCommand::class)
        ->args([
            service(SocketDaemon::class),
        ]);
};