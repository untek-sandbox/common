<?php

namespace Untek\User\Authentication\Domain\Interfaces\Services;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;

interface CredentialServiceInterface
{

    /**
     * @param string $credential
     * @param array|null $types
     * @return array|CredentialEntity[]
     */
    public function findByCredential(string $credential, array $types = null): array;

    /**
     * @param int $userId
     * @param array|null $types
     * @return array|CredentialEntity[]
     */
    public function findByUserId(int $userId, array $types = null): array;
}
