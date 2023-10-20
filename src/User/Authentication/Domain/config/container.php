<?php

use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Untek\User\Authentication\Domain\Interfaces\AuthorizationTokenGeneratorInterface;
use Untek\User\Authentication\Domain\Libs\SafeUriAuthorizationTokenGenerator;

return [
    'definitions' => [

    ],
    'singletons' => [
        PasswordHasherInterface::class => NativePasswordHasher::class,
        Security::class => \Untek\User\Authentication\Symfony4\Components\Core\Security::class,
        TokenStorageInterface::class => TokenStorage::class,
        'security.token_storage' => TokenStorageInterface::class,
        AuthorizationTokenGeneratorInterface::class => SafeUriAuthorizationTokenGenerator::class,

        'Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface' => 'Untek\User\Authentication\Domain\Services\BearerTokenService',
//        'Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface' => 'Untek\User\Authentication\Domain\Services\JwtTokenService',
        'Untek\User\Authentication\Domain\Interfaces\Services\AuthServiceInterface' => 'Untek\User\Authentication\Domain\Services\AuthService',
        'Untek\\User\\Authentication\\Domain\\Interfaces\\Services\\ImitationAuthServiceInterface' => 'Untek\\User\\Authentication\\Domain\\Services\\ImitationAuthAuthService',
        'Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface' => 'Untek\User\Authentication\Domain\Services\CredentialService',
        'Untek\User\Authentication\Domain\Interfaces\Repositories\CredentialRepositoryInterface' => 'Untek\User\Authentication\Domain\Repositories\Eloquent\CredentialRepository',
        'Untek\User\Authentication\Domain\Interfaces\Repositories\TokenRepositoryInterface' => 'Untek\User\Authentication\Domain\Repositories\Eloquent\TokenRepository',
    ],
];
