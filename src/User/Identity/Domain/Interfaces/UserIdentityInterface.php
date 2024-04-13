<?php

namespace Untek\User\Identity\Domain\Interfaces;

interface UserIdentityInterface
{

    public function getId(): int;

    public function isEnabled(): bool;
}
