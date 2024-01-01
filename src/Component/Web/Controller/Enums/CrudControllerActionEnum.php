<?php

namespace Untek\Component\Web\Controller\Enums;

use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class CrudControllerActionEnum
{

    const INDEX = 'index';
    const VIEW = 'view';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const CREATE = 'create';
}
