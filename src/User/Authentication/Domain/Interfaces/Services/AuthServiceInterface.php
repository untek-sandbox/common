<?php

namespace Untek\User\Authentication\Domain\Interfaces\Services;

use Untek\Model\Validator\Exceptions\UnprocessibleEntityException;
use Untek\User\Authentication\Domain\Entities\TokenValueEntity;
use Untek\User\Authentication\Domain\Forms\AuthForm;

interface AuthServiceInterface
{
    /**
     * @param AuthForm $form
     * @return TokenValueEntity
     * @throws UnprocessibleEntityException
     */
    public function tokenByForm(AuthForm $form): TokenValueEntity;
}
