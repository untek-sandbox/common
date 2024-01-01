<?php

namespace Untek\Kaz\Iin;

use Untek\Core\Bundle\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function getName(): string
    {
        return 'iin';
    }

    public function rbac(): array
    {
        return [
            __DIR__ . '/Domain/config/rbac.php',
        ];
    }

    /*public function symfonyRpc(): array
    {
        return [
            __DIR__ . '/Rpc/config/routes.php',
        ];
    }*/

    public function i18next(): array
    {
        return [
            'kaz.iin' => __DIR__ . '/Domain/i18next/__lng__/__ns__.json',
        ];
    }
}
