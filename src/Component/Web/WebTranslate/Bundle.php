<?php

namespace Untek\Component\Web\WebTranslate;

use Untek\Core\Bundle\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function getName(): string
    {
        return 'webTranslate';
    }

    public function i18next(): array
    {
        return [
            'web' => __DIR__ . '/i18next/__lng__/__ns__.json',
        ];
    }
}
