<?php

namespace Untek\User\Authentication\Domain\Services;

use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Untek\Crypt\Base\Domain\Exceptions\InvalidPasswordException;
use Untek\Crypt\Base\Domain\Interfaces\Services\PasswordServiceInterface;

class PasswordService implements PasswordServiceInterface
{

    private $passwordHasher;

    public function __construct(PasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function validate(string $password, string $hash): bool
    {
        $isValidPassword = $this->passwordHasher->verify(trim($hash), trim($password));
        if (!$isValidPassword) {
            throw new InvalidPasswordException;
        }
        return $isValidPassword;
    }

    public function generateHash(string $password, int $cost = null): string
    {
        return $this->passwordHasher->hash($password, $cost);
    }

}
