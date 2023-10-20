<?php

namespace Untek\User\Authentication\Domain\Model;

class Token
{

    private ?int $identityId;
    private string $value;
    private string $type;
    private string $token;

    public function __construct(?int $identityId, string $value, string $type = 'bearer')
    {
        $this->identityId = $identityId;
        $this->value = $value;
        $this->type = $type;
        $this->token = $type . ' ' . $value;
    }

    public function getIdentityId(): int
    {
        return $this->identityId;
    }

    /*public function setIdentityId(int $identityId): void
    {
        $this->identityId = $identityId;
    }*/

    public function getValue(): string
    {
        return $this->value;
    }

    /*public function setValue(string $value): void
    {
        $this->value = $value;
    }*/

    public function getType(): string
    {
        return $this->type;
    }

    /*public function setType(string $type): void
    {
        $this->type = $type;
    }*/

    public function getToken(): string
    {
        return $this->token;
    }

    /*public function setToken(string $token): void
    {
        $this->token = $token;
    }*/
}