<?php

namespace Untek\User\Authentication\Domain\Interfaces\Repositories;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Model\Repository\Interfaces\CrudRepositoryInterface;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;
use Untek\User\Authentication\Domain\Enums\CredentialTypeEnum;

interface CredentialRepositoryInterface extends CrudRepositoryInterface
{

    /**
     * @param int $identityId
     * @param array|null $types
     * @return Enumerable | CredentialEntity[]
     */
    public function allByIdentityId(int $identityId, array $types = null): Enumerable;

    /**
     * @param string $credential
     * @param string $type
     * @return CredentialEntity
     * @throws NotFoundException
     */
    public function findOneByCredential(string $credential, string|array $type = CredentialTypeEnum::LOGIN): CredentialEntity;

    public function findOneByCredentialValue(string $credential): CredentialEntity;

    /**
     * @param string $validation
     * @param string $type
     * @return CredentialEntity
     * @throws NotFoundException
     */
    public function findOneByValidation(string $validation, string $type): CredentialEntity;
}

