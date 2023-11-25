<?php

namespace Untek\User\Identity\Domain\Model;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class InMemoryUser implements
    UserInterface,
//    EquatableInterface,
    \Stringable
{

    private int $id;
    private string $username;
    private bool $enabled;
    private array $roles;

    public function __construct(int $id, ?string $username, array $roles = [], bool $enabled = true)
    {
        if ('' === $username || null === $username) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }

        $this->id = $id;
        $this->username = $username;
        $this->enabled = $enabled;
        $this->roles = $roles;
    }

    public function __toString(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Returns the identifier for this user (e.g. its username or email address).
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function eraseCredentials(): void
    {
    }

    /*public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }

        $currentRoles = array_map('strval', (array) $this->getRoles());
        $newRoles = array_map('strval', (array) $user->getRoles());
        $rolesChanged = \count($currentRoles) !== \count($newRoles) || \count($currentRoles) !== \count(array_intersect($currentRoles, $newRoles));
        if ($rolesChanged) {
            return false;
        }

        if ($this->getUserIdentifier() !== $user->getUserIdentifier()) {
            return false;
        }

        if ($this->isEnabled() !== $user->isEnabled()) {
            return false;
        }

        return true;
    }*/
}

