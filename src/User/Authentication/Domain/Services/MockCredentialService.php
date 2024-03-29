<?php

namespace Untek\User\Authentication\Domain\Services;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;
use Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface;

class MockCredentialService implements CredentialServiceInterface
{
    public function __construct(private array $items, private array $credentialTypes)
    {
    }

    public function findByCredential(string $credential, array $types = null): array
    {
        $types = $types ?: $this->credentialTypes;
        $credentialsCollection = [];
        foreach ($this->items as $item) {
            foreach ($types as $type) {
                if ($item['credential'] == $credential && $item['type'] == $type) {
                    $credentialEntity = new CredentialEntity();
                    $credentialEntity->setIdentityId($item['user_id']);
                    $credentialEntity->setCredential($item['credential']);
                    $credentialEntity->setType($item['type']);
                    $credentialEntity->setValidation($item['validation']);
                    $credentialsCollection[] = $credentialEntity;
                }
            }
        }
        return $credentialsCollection;
    }
}
