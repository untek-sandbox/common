<?php

namespace Untek\User\Authentication\Domain\Libs;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\EventDispatcher\Traits\EventDispatcherTrait;
use Untek\Crypt\Base\Domain\Exceptions\InvalidPasswordException;
use Untek\Crypt\Base\Domain\Services\PasswordService;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;

class CredentialsPasswordValidator
{

    use EventDispatcherTrait;

    public function __construct(
        private PasswordService $passwordService,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->setEventDispatcher($eventDispatcher);
    }

    public function isValidPassword(Enumerable $credentials, string $password): bool
    {
        foreach ($credentials as $credentialEntity) {
            $isValid = $this->isValidPasswordByCredential($credentialEntity, $password);
            if ($isValid) {
                return true;
            }
        }
        return false;
    }

    protected function isValidPasswordByCredential(CredentialEntity $credentialEntity, string $password): bool
    {
        try {
            $this->passwordService->validate($password, $credentialEntity->getValidation());
            return true;
        } catch (InvalidPasswordException $e) {
            return false;
        }
    }
}
