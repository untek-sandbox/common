<?php

namespace Untek\User\Authentication\Infrastructure\Libs;

use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;
use Untek\User\Authentication\Domain\Exceptions\BadPasswordException;

class CredentialsPasswordValidator
{

    public function __construct(
        private PasswordHasherInterface $passwordHasher,
        private TranslatorInterface $translator,
    )
    {
    }

    public function isValidPassword(array $credentials, string $password): CredentialEntity
    {
        foreach ($credentials as $credentialEntity) {
            $isValid = $this->passwordHasher->verify(trim($credentialEntity->getValidation()), trim($password));
            if ($isValid) {
                return $credentialEntity;
            }
        }
        throw new BadPasswordException($this->translator->trans('incorrectPassword', [], 'user'));
    }
}
