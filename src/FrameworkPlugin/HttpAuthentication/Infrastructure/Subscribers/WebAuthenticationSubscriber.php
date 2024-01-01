<?php

namespace Untek\FrameworkPlugin\HttpAuthentication\Infrastructure\Subscribers;

use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\FrameworkPlugin\HttpAuthentication\Application\Services\WebAuthentication;

class WebAuthenticationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private WebAuthentication $webAuthentication,
        private UserProviderInterface $userProvider,
        private TokenStorageInterface $tokenStorage,
        private AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 128],
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $identity = $this->webAuthentication->getUser($event->getRequest());
        if (!empty($identity)) {
            $token = new TestBrowserToken([], $identity);
        } else {
            $token = new NullToken();
        }
        $this->tokenStorage->setToken($token);
    }
}
