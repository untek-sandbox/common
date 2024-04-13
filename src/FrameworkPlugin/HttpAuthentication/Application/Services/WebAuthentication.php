<?php

namespace Untek\FrameworkPlugin\HttpAuthentication\Application\Services;

use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class WebAuthentication
{
    const COOKIE_NAME = 'identityId';

    const SESSION_NAME = 'user.identity';

    public function __construct(
        private UserProviderInterface $userProvider,
        private TokenStorageInterface $tokenStorage,
        private AuthorizationCheckerInterface $authorizationChecker,
        private SessionInterface $session
    ) {
        /*if (!$this->session->isStarted()) {
            $this->session->start();
        }*/
    }

    public function getUser(Request $request): ?UserInterface
    {
        try {
            $identity = $this->loadFromSession();
        } catch (UserNotFoundException) {
            try {
                $identity = $this->loadFromCookie($request);
                $this->saveToSession($identity);
            } catch (UserNotFoundException) {
            }
        }
        return $identity ?? null;
    }

    public function login(Response $response, UserInterface $user, bool $isRemember = false)
    {
        if ($isRemember) {
            $cookie = $this->createCookie($user);
            $response->headers->setCookie($cookie);
        }
        $this->saveToSession($user);
    }

    public function logout(Response $response)
    {
        $this->removeCookie($response);
        $this->removeSession();
    }

    public function remember()
    {
    }

    /**
     * @param Request $request
     * @return UserInterface
     * @throws UserNotFoundException
     */
    protected function loadFromCookie(Request $request): UserInterface
    {
        $identityIdCookie = $request->cookies->get(self::COOKIE_NAME);
        if ($identityIdCookie) {
            try {
                $encoder = new SignCookieEncoder(getenv('CSRF_TOKEN_ID'));
                $identityId = $encoder->decode($identityIdCookie);
                return $this->userProvider->loadUserByIdentifier($identityId);
            } catch (Exception $e) {
                throw new UserNotFoundException($e->getMessage());
            }
        }
        throw new UserNotFoundException();
    }

    protected function createCookie(UserInterface $user): Cookie
    {
        $encoder = new SignCookieEncoder(getenv('CSRF_TOKEN_ID'));
        $hashedValue = $encoder->encode($user->getUserIdentifier());
        return new Cookie(self::COOKIE_NAME, $hashedValue, new DateTime('+ 3650 day'));
    }

    protected function removeCookie(Response $response): void
    {
        $response->headers->clearCookie(self::COOKIE_NAME);
    }

    /**
     * @return UserInterface
     * @throws UserNotFoundException
     */
    protected function loadFromSession(): UserInterface
    {
        $identityArray = $this->session->get(self::SESSION_NAME);
        if (!empty($identityArray['identifier'])) {
            return $this->userProvider->loadUserByIdentifier($identityArray['identifier']);
        }
        throw new UserNotFoundException();
    }

    protected function saveToSession(UserInterface $user): void
    {
        $data = [
            'identifier' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
        ];
        $this->session->set(self::SESSION_NAME, $data);
    }

    protected function removeSession(): void
    {
        $this->session->remove(self::SESSION_NAME);
    }
}
