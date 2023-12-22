<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Framework\Socket\Domain\Repositories\Ram\ConnectionRepository;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\Framework\Socket\Symfony4\Commands\SocketCommand;
use Untek\Framework\Socket\Domain\Libs\SocketDaemon;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(ConnectionRepository::class, ConnectionRepository::class);
    $services->set(SocketDaemon::class, SocketDaemon::class)
        ->args([
            service(ConnectionRepository::class),
//            service(UserProviderInterface::class),
//            service(IdentityRepositoryInterface::class),
            service(TokenServiceInterface::class),
//            service(TokenStorageInterface::class),
        ]);
    $services->set(SocketCommand::class, SocketCommand::class)
    ->args([
        service(SocketDaemon::class),
    ]);
};