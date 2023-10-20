<?php

namespace Untek\User\Authentication\Domain\Services;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\Component\I18Next\Facades\I18Next;
use Untek\Core\Collection\Libs\Collection;
use Untek\Core\EventDispatcher\Traits\EventDispatcherTrait;
use Untek\Model\Validator\Entities\ValidationErrorEntity;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Model\Validator\Exceptions\UnprocessibleEntityException;
use Untek\Model\Validator\Helpers\ValidationHelper;
use Untek\Model\Validator\Interfaces\ValidatorInterface;
use Untek\User\Authentication\Domain\Entities\TokenValueEntity;
use Untek\User\Authentication\Domain\Enums\AuthEventEnum;
use Untek\User\Authentication\Domain\Events\AuthEvent;
use Untek\User\Authentication\Domain\Forms\AuthForm;
use Untek\User\Authentication\Domain\Interfaces\Services\AuthServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\User\Authentication\Domain\Libs\CredentialsPasswordValidator;

class MockAuthService implements AuthServiceInterface
{
    use EventDispatcherTrait;

    public function __construct(
        private UserProviderInterface $userProvider,
        private CredentialsPasswordValidator $credentialsPasswordValidator,
        private TokenServiceInterface $tokenService,
        private CredentialServiceInterface $credentialService,
        private LoggerInterface $logger,
        private ValidatorInterface $validator,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->setEventDispatcher($eventDispatcher);
    }

    public function tokenByForm(AuthForm $loginForm): TokenValueEntity
    {
        $userEntity = $this->getIdentityByForm($loginForm);

        $this->logger->info('auth tokenByForm');
        $tokenEntity = $this->tokenService->getTokenByIdentity($userEntity);
        $tokenEntity->setIdentity($userEntity);
        return $tokenEntity;
    }

    private function getIdentityByForm(AuthForm $loginForm): UserInterface
    {
//        ValidationHelper::validateEntity($loginForm);
        $this->validator->validateEntity($loginForm);

        $authEvent = new AuthEvent($loginForm);
        $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::BEFORE_AUTH);
        try {
            $userEntity = $this->userProvider->loadUserByIdentifier($loginForm->getLogin());
        } catch (UserNotFoundException $e) {
            $message = 'User not found.';
            throw UnprocessableEntityException::create($message, null, [], $loginForm, '[login]', $loginForm->getLogin());
            /*$errorCollection = new Collection();
            $ValidationErrorEntity = new ValidationErrorEntity;
            $ValidationErrorEntity->setField('login');
            $ValidationErrorEntity->setMessage(I18Next::t('authentication', 'auth.user_not_found'));
            $errorCollection->add($ValidationErrorEntity);
            $exception = new UnprocessibleEntityException();
            $exception->setErrorCollection($errorCollection);
            $this->logger->warning('auth authenticationByForm');
            throw $exception;*/
        }

        $credentials = $this->credentialService->findAll($loginForm->getLogin(), 'login');

        $isValidPassword = $this->credentialsPasswordValidator->isValidPassword(
            $credentials,
            $loginForm->getPassword()
        );
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
       /* $errorCollection = new Collection();
        $ValidationErrorEntity = new ValidationErrorEntity(
            'password',
            'Incorrect password', // I18Next::t('authentication', 'auth.incorrect_password')
        );
        $errorCollection->add($ValidationErrorEntity);*/
        throw UnprocessableEntityException::create('Incorrect password', null, [], null, '[password]', null);
//        $exception = new UnprocessibleEntityException();
//        $exception->setErrorCollection($errorCollection);
//        throw $exception;
    }
}
