<?php

namespace Untek\FrameworkPlugin\RestApiAuthentication\Infrastructure\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\User\Authentication\Domain\Authentication\Token\ApiToken;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;

class RestApiAuthenticationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private IdentityRepositoryInterface $identityRepository,
        private TokenServiceInterface $tokenService,
//        private UserProviderInterface $userProvider,
        private TokenStorageInterface $tokenStorage,
//        private AuthorizationCheckerInterface $authorizationChecker,
        private string $headerKeyName = 'Authorization'
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 128],
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $credentials = $event->getRequest()->headers->get($this->headerKeyName);

        if (empty($credentials)) {
            $token = new NullToken();
            $this->tokenStorage->setToken($token);
            return;
        }

        try {
            $userId = $this->tokenService->getIdentityIdByToken($credentials);
            $identity = $this->identityRepository->getUserById($userId);
            $token = new ApiToken($identity, 'main', $identity->getRoles(), $credentials);
            $this->tokenStorage->setToken($token);
        } catch (UserNotFoundException $e) {
            throw new AuthenticationException('Bad token');
        }
    }
}
