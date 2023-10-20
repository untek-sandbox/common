#  

Декларировать в конфиге контейнера:

```php

use Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\User\Authentication\Domain\Services\MockCredentialService;
use Untek\User\Authentication\Domain\Services\MockTokenService;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;

$services->set(CredentialServiceInterface::class, MockCredentialService::class)
    ->args(
        [
            include __DIR__ . '/../../resources/file-db/user/user_credential.php',
        ]
    );

$services->set(TokenServiceInterface::class, MockTokenService::class)
    ->args(
        [
            service(InMemoryUserProvider::class),
            include __DIR__ . '/../../resources/file-db/user/user_token.php',
        ]
    );
$services->set(InMemoryUserProvider::class, InMemoryUserProvider::class)
    ->args(
        [
            include __DIR__ . '/../../resources/file-db/user/user_identity.php',
        ]
    );
```