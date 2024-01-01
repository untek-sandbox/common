<?php

namespace Untek\Component\Web\HtmlRender\Infrastructure;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Core\Kernel\Bundle\BaseBundle;

class ViewBundle extends BaseBundle
{
    public function getName(): string
    {
        return 'view';
    }

    public function build(ContainerBuilder $containerBuilder)
    {
        $this->importServices($containerBuilder, __DIR__ . '/../resources/config/services/main.php');
    }
}
