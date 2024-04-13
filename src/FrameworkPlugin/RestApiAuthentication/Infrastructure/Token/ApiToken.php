<?php

namespace Untek\FrameworkPlugin\RestApiAuthentication\Infrastructure\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiToken extends AbstractToken
{
    private string $firewallName;
    private ?string $credentials;

    public function __construct(UserInterface $user, string $firewallName, array $roles = [], string $credentials = null)
    {
        parent::__construct($roles);

        if ('' === $firewallName) {
            throw new \InvalidArgumentException('$firewallName must not be empty.');
        }

        $this->setUser($user);
        $this->firewallName = $firewallName;
        $this->credentials = $credentials;
    }

    public function getFirewallName(): string
    {
        return $this->firewallName;
    }

    public function getCredentials(): ?string
    {
        return $this->credentials;
    }

    public function __serialize(): array
    {
        return [null, $this->firewallName, parent::__serialize()];
    }

    public function __unserialize(array $data): void
    {
        [, $this->firewallName, $parentData] = $data;
        $parentData = \is_array($parentData) ? $parentData : unserialize($parentData);
        parent::__unserialize($parentData);
    }
}
