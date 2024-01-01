<?php

namespace Untek\Component\Web\Form;

use Untek\Core\Bundle\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function getName(): string
    {
        return 'webForm';
    }

    public function container(): array
    {
        return [
            __DIR__ . '/config/container.php',
        ];
    }
}
