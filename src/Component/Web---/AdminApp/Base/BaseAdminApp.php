<?php

namespace Untek\Component\Web\AdminApp\Base;

use Untek\Component\Web\WebApp\Base\BaseWebApp;

abstract class BaseAdminApp extends BaseWebApp
{

    public function appName(): string
    {
        return 'admin';
    }

    public function import(): array
    {
        return ['i18next', 'container', 'entityManager', 'eventDispatcher', 'rbac', 'symfonyAdmin'];
    }
}
