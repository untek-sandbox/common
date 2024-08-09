<?php

namespace Untek\User\Authentication\Domain\Services;

use Untek\User\Authentication\Domain\Entities\CredentialEntity;
use Untek\User\Authentication\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Model\Service\Base\BaseService;

class CredentialService extends BaseService implements CredentialServiceInterface
{

    public function __construct(CredentialRepositoryInterface $repository)
    {
        $this->setRepository($repository);
    }

    public function findOneByIdentityIdAndType(int $identityId, string $type): CredentialEntity
    {
        $all = $this->getRepository()->allByIdentityId($identityId, [$type]);
        if ($all->count() == 0) {
            throw new NotFoundException();
        }
        return $all->first();
    }

    public function findOneByCredentialValue(string $credential): CredentialEntity
    {
        return $this->getRepository()->findOneByCredentialValue($credential);
    }

    public function hasByCredentialValue(string $credential): bool
    {
        try {
            $this->findOneByCredentialValue($credential);
            return true;
        } catch (NotFoundException $e) {
            return false;
        }
    }
}
