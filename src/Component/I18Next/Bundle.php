<?php

namespace Untek\Component\I18Next;

use Untek\Core\Bundle\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function getName(): string
    {
        return 'i18next';
    }

    public function container(): array
    {
        return [
            __DIR__ . '/config/container.php',
        ];
    }
}
