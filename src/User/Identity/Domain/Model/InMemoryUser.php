<?php

namespace Untek\User\Identity\Domain\Model;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Untek\User\Identity\Domain\Interfaces\UserIdentityInterface;

final class InMemoryUser implements
    UserInterface,
    UserIdentityInterface,
//    EquatableInterface,
    \Stringable
{

    private ?int $id;
    private string $username;
    private bool $enabled;
    private array $roles;
    private array $assignments;

    public function __construct(?int $id, ?string $username, array $roles = [], bool $enabled = true)
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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
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

    public function getAssignments(): array
    {
        return $this->assignments;
    }

    public function setAssignments(array $assignments): void
    {
        $this->assignments = $assignments;
    }
}
