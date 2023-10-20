<?php

namespace Untek\Crypt\Base;

use Untek\Core\Bundle\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function getName(): string
    {
        return 'crypt_base';
    }

    public function container(): array
    {
        return [
            __DIR__ . '/Domain/config/container-new.php',
        ];
    }
}
