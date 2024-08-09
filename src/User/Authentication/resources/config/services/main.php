<?php

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\ChainUserProvider;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\Crypt\Base\Domain\Services\PasswordService;
use Untek\Model\Validator\Interfaces\ValidatorInterface;
use Untek\User\Authentication\Application\Handlers\GenerateTokenByPasswordCommandHandler;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\User\Authentication\Domain\Libs\CredentialsPasswordValidator;
use Untek\User\Authentication\Domain\UserProviders\MockApiTokenUserProvider;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure()
    ->bind('$credentialTypes', ['login', 'phone'])
    ;

    $services
        ->load('Untek\User\Authentication\\', __DIR__ . '/../../..')
        ->exclude([
            __DIR__ . '/../../../{resources,Domain,Application/Commands,Application/Queries,Application/Validators}',
            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Schema.php,Normalizer.php}',
            __DIR__ . '/../../../**/{Dto}',
            __DIR__ . '/../../../Infrastructure/Services',
            __DIR__ . '/../../../Infrastructure/UserProviders',
        ]);

    $services->set(TokenStorageInterface::class, TokenStorage::class);
    $services->alias('security.token_storage', TokenStorageInterface::class);

    $services->set(PasswordHasherInterface::class, NativePasswordHasher::class);
    $services->set(PasswordService::class);
    $services->set(CredentialsPasswordValidator::class);
    $services->set(MockApiTokenUserProvider::class);
    $services->set(UserProviderInterface::class, ChainUserProvider::class)
        ->args(
            [
                [
                    service(MockApiTokenUserProvider::class),
                ]
            ]
        );




    /*$services->set(GenerateTokenByPasswordCommandHandler::class)
        ->args([
            service(TranslatorInterface::class),
            service(UserProviderInterface::class),
            service(CredentialsPasswordValidator::class),
            service(TokenServiceInterface::class),
            service(CredentialServiceInterface::class),
            service(LoggerInterface::class),
            service(ValidatorInterface::class),
//        service(\Psr\EventDispatcher\EventDispatcherInterface::class),
            service(IdentityRepositoryInterface::class),
            ['login', 'phone'],
        ])//        ->tag('cqrs.handler')
    ;*/

};