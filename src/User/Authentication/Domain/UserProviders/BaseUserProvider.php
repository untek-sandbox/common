<?php

namespace Untek\User\Authentication\Domain\UserProviders;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\Core\Contract\Common\Exceptions\InvalidMethodParameterException;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Core\Contract\Common\Exceptions\NotSupportedException;
use Untek\Core\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\EntityManager\Traits\EntityManagerAwareTrait;
use Untek\Model\Repository\Interfaces\FindOneInterface;
use Untek\User\Authentication\Domain\Helpers\TokenHelper;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;

abstract class BaseUserProvider implements UserProviderInterface
{

    use EntityManagerAwareTrait;

    protected function findOneIdentityById(int $userId): ?IdentityEntityInterface
    {
        /** @var FindOneInterface $repository */
        $repository = $this->getEntityManager()->getRepository(IdentityEntityInterface::class);
        $identityEntity = $repository->findOneById($userId);
        if (!$identityEntity->getRoles()) {
            $this->getEntityManager()->loadEntityRelations($identityEntity, ['assignments']);
        }
//        if (!$identityEntity->getCredentials()) {
            $this->getEntityManager()->loadEntityRelations($identityEntity, ['credentials']);
//        }
//        dd($identityEntity);
        return $identityEntity;
    }

    protected function notFound(string $identifier, string $errorMessage = 'User does not exist.') {
        //            $errorMessage = sprintf('Username "%s" does not exist.', $identifier);
        $ex = new UserNotFoundException($errorMessage);
        $ex->setUserIdentifier($identifier);
        throw $ex;
    }
}
