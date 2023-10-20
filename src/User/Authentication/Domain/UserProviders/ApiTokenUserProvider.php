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
use Untek\Model\Repository\Interfaces\FindOneInterface;
use Untek\User\Authentication\Domain\Helpers\TokenHelper;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;

class ApiTokenUserProvider extends BaseUserProvider implements UserProviderInterface
{

    public function __construct(
        private TokenServiceInterface $tokenService,
        EntityManagerInterface $em,
        private LoggerInterface $logger
    ) {
        $this->setEntityManager($em);
    }

    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
    }

    public function supportsClass(string $class)
    {
        return true;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        try {
            $tokenValueEntity = TokenHelper::parseToken($identifier);
        } catch (InvalidMethodParameterException $e) {
            $this->notFound($identifier);
        }

        // todo: сделать обработчики токенов разных типов
        if ($tokenValueEntity->getType() == 'bearer') {
            try {
                $userId = $this->tokenService->getIdentityIdByToken($identifier);
                /** @var IdentityEntityInterface $userEntity */
                $userEntity = $this->findOneIdentityById($userId);
            } catch (NotFoundException $e) {
                $this->notFound($identifier);
            }
//            $this->logger->info('auth authenticationByToken');
            return $userEntity;
        } else {
            $errorMessage = 'Token type "' . $tokenValueEntity->getType() . '" not supported in ' . get_class($this);
            $this->notFound($identifier, $errorMessage);
        }
    }
}
