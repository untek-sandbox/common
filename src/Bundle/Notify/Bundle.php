<?php

namespace Untek\Bundle\Notify;

use Untek\Core\Bundle\Base\BaseBundle;

// todo: отделить flash, toastr
class Bundle extends BaseBundle
{

    public function getName(): string
    {
        return 'notify';
    }

    public function container(): array
    {
        return [
            __DIR__ . '/Domain/config/container.php',
        ];
    }
}
