<?php

namespace Untek\Component\Web\WebApp\Subscribers;

use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Contract\Common\Exceptions\InvalidConfigException;
use Untek\Model\Entity\Helpers\EntityHelper;
use Untek\Component\Web\Controller\Interfaces\ControllerAccessInterface;
use Untek\Component\Web\SignedCookie\Libs\CookieValue;
use Untek\User\Authentication\Domain\Enums\WebCookieEnum;
use Untek\User\Authentication\Domain\Interfaces\Services\AuthServiceInterface;
use Untek\User\Identity\Domain\Interfaces\Services\IdentityServiceInterface;
use Untek\User\Rbac\Domain\Interfaces\Services\ManagerServiceInterface;

DeprecateHelper::hardThrow();

class WebFirewallSubscriber implements EventSubscriberInterface
{

//    private $authService;
    private $identityService;
//    private $session;
//    private $security;
//    private $managerService;

    public function __construct(
        private UserProviderInterface $userProvider,
        private TokenStorageInterface $tokenStorage,
        private AuthorizationCheckerInterface $authorizationChecker,
        private SessionInterface $session
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 128],
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(\Symfony\Component\HttpKernel\Event\ControllerEvent $event)
    {
        $controller = $event->getController();
        list($controllerInstance, $actionName) = $controller;

        if (!$controllerInstance instanceof ControllerAccessInterface) {
            //throw new InvalidConfigException('Controller not instance of "ControllerAccessInterface".');
        }
        if ($controllerInstance instanceof ControllerAccessInterface) {
            $access = $controllerInstance->access();
            $actionPermissions = ArrayHelper::getValue($access, $actionName);
            if (empty($actionPermissions)) {
                throw new InvalidConfigException('Empty permissions.');
            }
            if ($actionPermissions) {
                try {
                    $isGranted = $this->authorizationChecker->isGranted($actionPermissions);
                    if(!$isGranted) {
                        throw new AccessDeniedException();
                    }
                } catch (AuthenticationException $exception) {
                }
//                $this->managerService->checkMyAccess($actionPermissions);
            }
        }
    }

    public function onKernelRequest(RequestEvent $event)
    {

        $request = $event->getRequest();
        $token = new NullToken();
        $identityArray = $this->session->get('user.identity');
//        dd($identityArray);

        if (!$identityArray) {
            $identityIdCookie = $event->getRequest()->cookies->get(WebCookieEnum::IDENTITY_ID);
            if ($identityIdCookie) {
                try {
                    $cookieValue = new CookieValue(getenv('CSRF_TOKEN_ID'));
                    $identityId = $cookieValue->decode($identityIdCookie);
                    $identity = $this->identityService->findOneById($identityId);

                    $token = new TestBrowserToken([], $identity);

                    //$this->authService->setIdentity($identity);
                    $this->session->set('user.identity', EntityHelper::toArray($identity));
                } catch (\DomainException $e) {}
            }
        } else {
            $identity = $this->userProvider->loadUserByIdentifier($identityArray['identifier']);
//            dd($identity);
//            $identity = $this->identityService->findOneById($identityArray['identifier']);
            $token = new TestBrowserToken([], $identity);
        }
        $this->tokenStorage->setToken($token);
    }
}
