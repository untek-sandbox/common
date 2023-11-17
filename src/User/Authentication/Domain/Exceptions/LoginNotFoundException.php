<?php

namespace Untek\User\Authentication\Domain\Exceptions;

use Exception;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class LoginNotFoundException extends Exception
{

}