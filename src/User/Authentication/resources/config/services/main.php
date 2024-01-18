<?php

use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\User\Authentication\Application\Handlers\GenerateTokenByPasswordCommandHandler;
use Untek\User\Authentication\Presentation\Http\RestApi\Controllers\GenerateTokenByPasswordController;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\User\ChainUserProvider;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
//use Untek\FrameworkPlugin\HttpAuthentication\Application\Services\WebAuthentication;
use Untek\Crypt\Base\Domain\Services\PasswordService;
use Untek\Model\Validator\Interfaces\ValidatorInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\AuthServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\User\Authentication\Domain\Libs\CredentialsPasswordValidator;
use Untek\User\Authentication\Domain\Services\MockAuthService;
use Untek\User\Authentication\Domain\Services\MockCredentialService;
use Untek\User\Authentication\Domain\Services\MockTokenService;
use Untek\User\Authentication\Domain\UserProviders\MockApiTokenUserProvider;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Untek\Component\App\Services\ControllerAccessChecker;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(TokenStorageInterface::class, TokenStorage::class);
    $services->alias('security.token_storage', TokenStorageInterface::class);

    $services->set(GenerateTokenByPasswordCommandHandler::class, GenerateTokenByPasswordCommandHandler::class)
    ->args([
        service(UserProviderInterface::class),
        service(CredentialsPasswordValidator::class),
        service(TokenServiceInterface::class),
        service(CredentialServiceInterface::class),
        service(LoggerInterface::class),
        service(ValidatorInterface::class),
//        service(\Psr\EventDispatcher\EventDispatcherInterface::class),
        service(IdentityRepositoryInterface::class),
        ['login', 'phone'],
    ]);
    $services->set(GenerateTokenByPasswordController::class, GenerateTokenByPasswordController::class)
        ->args([
            service(CommandBusInterface::class),
        ]);


    $services->set(ControllerAccessChecker::class, ControllerAccessChecker::class)
        ->args([
            service(ContainerInterface::class),
        ]);

    /*$services->set(AuthIdentityController::class, AuthIdentityController::class)
        ->args(
            [
                service(ContainerInterface::class),
//                service(TokenStorageInterface::class),
                service(RoleHierarchy::class),
                service(AccessDecisionManagerInterface::class),
            ]
        );*/

    /*$services->set(CredentialServiceInterface::class, MockCredentialService::class)
        ->args(
            [
                include __DIR__ . '/../../../../../../../resources/file-db/user/user_credential.php',
                ['login', 'phone'],
            ]
        );

    $services->set(TokenServiceInterface::class, MockTokenService::class)
        ->args(
            [
                service(InMemoryUserProvider::class),
                include __DIR__ . '/../../../../../../../resources/file-db/user/user_token.php',
            ]
        );*/
    $services->set(PasswordHasherInterface::class, NativePasswordHasher::class);

    $services->set(PasswordService::class, PasswordService::class)
        ->args(
            [
                service(PasswordHasherInterface::class),
            ]
        );

    $services->set(CredentialsPasswordValidator::class, CredentialsPasswordValidator::class)
        ->args(
            [
                service(PasswordService::class),
                service(EventDispatcherInterface::class),
            ]
        );

    $services->set(MockApiTokenUserProvider::class, MockApiTokenUserProvider::class)
        ->args(
            [
                service(TokenServiceInterface::class),
                service(IdentityRepositoryInterface::class),
            ]
        );

    $services->set(UserProviderInterface::class, ChainUserProvider::class)
        ->args(
            [
                [
                    service(InMemoryUserProvider::class),
                    service(MockApiTokenUserProvider::class),
                ]
            ]
        );

    /*$services->set(InMemoryUserProvider::class, InMemoryUserProvider::class)
        ->args(
            [
                include __DIR__ . '/../../../../../../../resources/file-db/user/user_identity.php',
            ]
        );*/

    /*$services->set(WebAuthentication::class, WebAuthentication::class)
        ->args(
            [
                service(UserProviderInterface::class),
                service(TokenStorageInterface::class),
                service(AuthorizationCheckerInterface::class),
                service(SessionInterface::class),
            ]
        );*/

    /*$services->set(AuthServiceInterface::class, MockAuthService::class)
        ->args(
            [
                service(UserProviderInterface::class),
                service(CredentialsPasswordValidator::class),
                service(TokenServiceInterface::class),
                service(CredentialServiceInterface::class),
                service(LoggerInterface::class),
                service(ValidatorInterface::class),
                service(\Psr\EventDispatcher\EventDispatcherInterface::class),
            ]
        );*/
    /*$services->set(AuthController::class, AuthController::class)
        ->args(
            [
                service(AuthServiceInterface::class),
            ]
        );*/
};