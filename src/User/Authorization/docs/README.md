#    

Декларировать в конфиге контейнера:

```php
use Symfony\Component\Security\Core\Role\RoleHierarchy;

$services->set(RoleHierarchy::class, RoleHierarchy::class)
    ->args(
        [
            include __DIR__ . '/../../resources/file-db/user/user_roles.php',
        ]
    );
```