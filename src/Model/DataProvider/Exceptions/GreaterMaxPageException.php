<?php

namespace Untek\Model\DataProvider\Exceptions;

use Exception;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class GreaterMaxPageException extends Exception
{

}