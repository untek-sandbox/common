<?php

namespace Untek\Component\Web\WebApp;

use Untek\Core\Bundle\Base\BaseBundle;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class Bundle extends BaseBundle
{

    public function getName(): string
    {
        return 'webApp';
    }

    public function deps(): array
    {
        return [
            \Untek\Component\Web\Form\Bundle::class,
            \Untek\Component\Web\HtmlRender\Bundle::class,
            \Untek\FrameworkPlugin\HttpLayout\Infrastructure\Bundle::class,
        ];
    }

    public function container(): array
    {
        return [
            __DIR__ . '/config/container.php',
        ];
    }
}
