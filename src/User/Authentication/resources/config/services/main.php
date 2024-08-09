<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\ChainUserProvider;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\Crypt\Base\Domain\Services\PasswordService;
use Untek\User\Authentication\Infrastructure\Libs\CredentialsPasswordValidator;
use Untek\User\Authentication\Infrastructure\UserProviders\MockApiTokenUserProvider;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure()
        ->bind('$credentialTypes', ['login', 'phone']);

    $services
        ->load('Untek\User\Authentication\\', __DIR__ . '/../../..')
        ->exclude([
            __DIR__ . '/../../../{resources,Domain,Application/Commands,Application/Queries,Application/Validators}',
            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Schema.php,Normalizer.php}',
            __DIR__ . '/../../../**/{Dto}',
        ]);

    $services->set(TokenStorageInterface::class, TokenStorage::class);
    $services->alias('security.token_storage', TokenStorageInterface::class);

    $services->set(PasswordService::class);
    $services->set(NativePasswordHasher::class);
    $services->alias(PasswordHasherInterface::class, NativePasswordHasher::class);

    $services->set(ChainUserProvider::class)
        ->args([
            [
                service(MockApiTokenUserProvider::class),
            ]
        ]);
    $services->alias(UserProviderInterface::class, ChainUserProvider::class);
};