<?php

namespace Untek\User\Authentication\Domain\Enums;

use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class CredentialTypeEnum
{

    const LOGIN = 'login';
    const EMAIL = 'email';
    const PHONE = 'phone';

}