<?php

namespace Untek\User\Authentication\Application\Handlers;

use Untek\User\Authentication\Application\Commands\GenerateTokenByPasswordCommand;
use Untek\User\Authentication\Application\Validators\GenerateTokenByPasswordCommandValidator;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;
use Untek\User\Authentication\Domain\Exceptions\BadPasswordException;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Untek\User\Authentication\Domain\Model\Token;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\Core\EventDispatcher\Traits\EventDispatcherTrait;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Model\Validator\Interfaces\ValidatorInterface;
use Untek\User\Authentication\Domain\Enums\AuthEventEnum;
use Untek\User\Authentication\Domain\Events\AuthEvent;
use Untek\User\Authentication\Domain\Forms\AuthForm;
use Untek\User\Authentication\Domain\Interfaces\Services\AuthServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\User\Authentication\Domain\Libs\CredentialsPasswordValidator;

class GenerateTokenByPasswordCommandHandler
{

//    use EventDispatcherTrait;

    /*public function __construct(private AuthServiceInterface $service)
    {
    }*/

    public function __construct(
        private UserProviderInterface $userProvider,
        private CredentialsPasswordValidator $credentialsPasswordValidator,
        private TokenServiceInterface $tokenService,
        private CredentialServiceInterface $credentialService,
        private LoggerInterface $logger,
        private ValidatorInterface $validator,
        private IdentityRepositoryInterface $identityRepository,
        private array $credentialTypes
//        EventDispatcherInterface $eventDispatcher,

    ) {
//        $this->setEventDispatcher($eventDispatcher);
    }

    /**
     * @param GenerateTokenByPasswordCommand $command
     * @return Token
     * @throws UserNotFoundException|BadPasswordException|UnprocessableEntityException
     */
    public function __invoke(GenerateTokenByPasswordCommand $command): Token
    {
        $validator = new GenerateTokenByPasswordCommandValidator();
        $validator->validate($command);

        $userEntity = $this->getIdentityByForm($command);

        $this->logger->info('auth tokenByForm');
        $tokenEntity = $this->tokenService->getTokenByIdentity($userEntity);
        $tokenEntity->setIdentity($userEntity);

        return new Token(null, $tokenEntity->getToken(), $tokenEntity->getType());
    }

    /**
     * @param GenerateTokenByPasswordCommand $command
     * @return UserInterface
     * @throws UserNotFoundException|BadPasswordException
     */
    private function getIdentityByForm(GenerateTokenByPasswordCommand $command): UserInterface
    {
//        ValidationHelper::validateEntity($loginForm);
//        $this->validator->validateEntity($loginForm);

//        $authEvent = new AuthEvent($loginForm);
//        $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::BEFORE_AUTH);

        /** @var CredentialEntity[] $credentials */
        $credentials = $this->credentialService->findAll($command->getLogin(), $this->credentialTypes);

        if($credentials->isEmpty()) {
            throw new UserNotFoundException('User not found.');
        }

        $credentialEntity = $this->credentialsPasswordValidator->isValidPassword(
            $credentials,
            $command->getPassword()
        );
        $userEntity = $this->identityRepository->getUserById($credentialEntity->getIdentityId());

        /*if (!$isValidPassword) {
            $this->logger->warning('auth verificationPassword');
//            $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::AFTER_AUTH_ERROR);
            throw new BadPasswordException('Incorrect password');
        }*/

//        $authEvent->setIdentityEntity($userEntity);
//        $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::AFTER_AUTH_SUCCESS);

//        print_r($credentials[0]->getIdentityId());

        return $userEntity;
    }
}