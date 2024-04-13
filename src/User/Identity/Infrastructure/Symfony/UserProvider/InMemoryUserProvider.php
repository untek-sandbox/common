<?php

namespace Untek\User\Identity\Infrastructure\Symfony\UserProvider;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class InMemoryUserProvider extends \Symfony\Component\Security\Core\User\InMemoryUserProvider implements UserProviderInterface
{
    /**
     * @var array<string, UserInterface>
     */
    private array $users = [];

    /**
     * The user array is a hash where the keys are usernames and the values are
     * an array of attributes: 'password', 'enabled', and 'roles'.
     *
     * @param array<string, array{password?: string, enabled?: bool, roles?: list<string>}> $users An array of users
     */
    public function __construct(array $users = [])
    {
        foreach ($users as $username => $attributes) {
            $password = $attributes['password'] ?? null;
            $enabled = $attributes['enabled'] ?? true;
            $roles = $attributes['roles'] ?? [];
            $user = new InMemoryUser($attributes['username'], $password, $roles, $enabled);

            $this->createUser($user);
        }
    }

    /**
     * Adds a new User to the provider.
     *
     * @return void
     *
     * @throws \LogicException
     */
    public function createUser(UserInterface $user)
    {
        if (!$user instanceof InMemoryUser) {
            trigger_deprecation('symfony/security-core', '6.3', 'Passing users that are not instance of "%s" to "%s" is deprecated, "%s" given.', InMemoryUser::class, __METHOD__, get_debug_type($user));
        }

        $userIdentifier = strtolower($user->getUserIdentifier());
        if (isset($this->users[$userIdentifier])) {
            throw new \LogicException('Another user with the same username already exists.');
        }

        $this->users[$userIdentifier] = $user;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->getUser($identifier);

        return new InMemoryUser($user->getUserIdentifier(), $user->getPassword(), $user->getRoles(), $user->isEnabled());
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof InMemoryUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_debug_type($user)));
        }

        $storedUser = $this->getUser($user->getUserIdentifier());
        $userIdentifier = $storedUser->getUserIdentifier();

        return new InMemoryUser($userIdentifier, $storedUser->getPassword(), $storedUser->getRoles(), $storedUser->isEnabled());
    }

    public function supportsClass(string $class): bool
    {
        return InMemoryUser::class == $class;
    }

    /**
     * Returns the user by given username.
     *
     * @return InMemoryUser change return type on 7.0
     *
     * @throws UserNotFoundException if user whose given username does not exist
     */
    protected function getUser(string $username): UserInterface
    {
        foreach ($this->users as $user) {
            /** @var UserInterface $user */
            if($user->getUserIdentifier() == strtolower($username)) {
                return $user;
            }
        }

        $ex = new UserNotFoundException(sprintf('Username "%s" does not exist.', $username));
        $ex->setUserIdentifier($username);
        throw $ex;
    }
}
