<?php

namespace Untek\User\Authentication\Domain\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Untek\Bundle\User\Domain\Entities\User;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Core\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use Untek\Core\EventDispatcher\Traits\EventDispatcherTrait;
use Untek\Crypt\Base\Domain\Exceptions\InvalidPasswordException;
use Untek\Crypt\Base\Domain\Services\PasswordService;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\Repository\Interfaces\FindOneInterface;
use Untek\Model\Repository\Traits\RepositoryAwareTrait;
use Untek\Model\Validator\Entities\ValidationErrorEntity;
use Untek\Model\Validator\Exceptions\UnprocessibleEntityException;
use Untek\Model\Validator\Helpers\ValidationHelper;
use Untek\Component\I18Next\Facades\I18Next;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;
use Untek\User\Authentication\Domain\Entities\TokenValueEntity;
use Untek\User\Authentication\Domain\Enums\AuthEventEnum;
use Untek\User\Authentication\Domain\Events\AuthEvent;
use Untek\User\Authentication\Domain\Forms\AuthForm;
use Untek\User\Authentication\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\AuthServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\User\Authentication\Domain\Libs\CredentialsPasswordValidator;
use Untek\User\Identity\Domain\Events\IdentityEvent;
use Untek\User\Identity\Domain\Interfaces\Repositories\IdentityRepositoryInterface;

class AuthService implements AuthServiceInterface
{

    use RepositoryAwareTrait;
    use EventDispatcherTrait;

    protected $tokenService;
//    protected $passwordService;
    protected $credentialRepository;
//    protected $identityRepository;
    protected $logger;
//    protected $identityEntity;
    protected $security;
    protected $em;

    public function __construct(
        IdentityRepositoryInterface $identityRepository,
        CredentialRepositoryInterface $credentialRepository,
//        PasswordService $passwordService,
        TokenServiceInterface $tokenService,
        EntityManagerInterface $em,
        Security $security,
        private UserProviderInterface $userProvider,
        private CredentialsPasswordValidator $credentialsPasswordValidator,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger,
        private TokenStorageInterface $tokenStorage
    ) {
//        $this->identityRepository = $identityRepository;
//        $this->passwordService = $passwordService;
        $this->setEventDispatcher($eventDispatcher);
        $this->credentialRepository = $credentialRepository;
        $this->logger = $logger;
        $this->tokenService = $tokenService;
        $this->security = $security;
        $this->em = $em;
//        $this->resetAuth();
    }

    protected function resetAuth()
    {
        $token = new NullToken();
//        $this->security->setToken($token);
        $this->tokenStorage->setToken($token);
    }

    protected function setIdentity(IdentityEntityInterface $identityEntity)
    {
        /*if (!$identityEntity->getRoles()) {
            $this->em->loadEntityRelations($identityEntity, ['assignments']);
        }*/
//        $token = new AnonymousToken([], $identityEntity);
        $token = new UsernamePasswordToken($identityEntity, 'main', $identityEntity->getRoles());
//        $this->security->setToken($token);
        $this->tokenStorage->setToken($token);

        //$event = new IdentityEvent($identityEntity);
        //$this->getEventDispatcher()->dispatch($event, AuthEventEnum::BEFORE_SET_IDENTITY);
//        $this->identityEntity = $identityEntity;
        //$this->getEventDispatcher()->dispatch($event, AuthEventEnum::AFTER_SET_IDENTITY);
    }

//    public function getIdentity(): ?IdentityEntityInterface
//    {
//        $identityEntity = null;
//        if ($this->security->getUser() != null) {
//            $identityEntity = $this->security->getUser();
//        } /*elseif($this->identityEntity) {
//            $identityEntity = $this->identityEntity;
//        }*/
//        $event = new IdentityEvent();
//        $event->setIdentityEntity($identityEntity);
//        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::BEFORE_GET_IDENTITY);
//        /*if($event->getIdentityEntity()) {
//            return $event->getIdentityEntity();
//        }*/
//        if ($this->isGuest()) {
//            throw new AuthenticationException();
//        }
//        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::AFTER_GET_IDENTITY);
//        return $event->getIdentityEntity();
//    }

    /*public function isGuest(): bool
    {
        $identityEntity = $this->security->getUser();
        if ($identityEntity != null) {
            return false;
        }
        $event = new IdentityEvent($identityEntity);
        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::BEFORE_IS_GUEST);
        if (is_bool($event->getIsGuest())) {
            return $event->getIsGuest();
        }
        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::AFTER_IS_GUEST);
        return true;
    }*/

    public function logout()
    {
        $identityEntity = $this->security->getUser();
        $event = new IdentityEvent($identityEntity);
        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::BEFORE_LOGOUT);

        $this->resetAuth();
        $this->logger->info('auth logout');
        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::AFTER_LOGOUT);
    }

    public function tokenByForm(AuthForm $loginForm): TokenValueEntity
    {
        $userEntity = $this->getIdentityByForm($loginForm);

        $this->logger->info('auth tokenByForm');
        //$authEvent = new AuthEvent($loginForm);
        $tokenEntity = $this->tokenService->getTokenByIdentity($userEntity);
        $tokenEntity->setIdentity($userEntity);
//        $this->em->loadEntityRelations($userEntity, ['assignments']);
        return $tokenEntity;
    }

    public function authByForm(AuthForm $authForm)
    {
        $userEntity = $this->getIdentityByForm($authForm);
        $this->setIdentity($userEntity);
    }

    protected function findOneIdentityById(int $userId): ?IdentityEntityInterface
    {
        /** @var FindOneInterface $repository */
        $repository = $this->em->getRepository(IdentityEntityInterface::class);
        return $repository->findOneById($userId);
    }

    private function getIdentityByForm(AuthForm $loginForm): IdentityEntityInterface
    {
        ValidationHelper::validateEntity($loginForm);
        $authEvent = new AuthEvent($loginForm);
        $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::BEFORE_AUTH);
        try {
            $userEntity = $this->userProvider->loadUserByIdentifier($loginForm->getLogin());
        } catch (UserNotFoundException $e) {
            $errorCollection = new Collection();
            $ValidationErrorEntity = new ValidationErrorEntity;
            $ValidationErrorEntity->setField('login');
            $ValidationErrorEntity->setMessage(I18Next::t('authentication', 'auth.user_not_found'));
            $errorCollection->add($ValidationErrorEntity);
            $exception = new UnprocessibleEntityException();
            $exception->setErrorCollection($errorCollection);
            $this->logger->warning('auth authenticationByForm');
            throw $exception;
        }

        $isValidPassword = $this->credentialsPasswordValidator->isValidPassword($userEntity->getCredentials(), $loginForm->getPassword());
        if (!$isValidPassword) {
            $this->logger->warning('auth verificationPassword');
            $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::AFTER_AUTH_ERROR);
            $this->incorrectPasswordException();
        }

        $authEvent->setIdentityEntity($userEntity);
        $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::AFTER_AUTH_SUCCESS);

        return $userEntity;
    }

    protected function incorrectPasswordException(): void
    {
        $errorCollection = new Collection();
        $ValidationErrorEntity = new ValidationErrorEntity(
            'password',
            I18Next::t('authentication', 'auth.incorrect_password')
        );
        $errorCollection->add($ValidationErrorEntity);
        $exception = new UnprocessibleEntityException;
        $exception->setErrorCollection($errorCollection);
        throw $exception;
    }
}
