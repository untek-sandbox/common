<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Breadcrumb\Infrastructure;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Core\Kernel\Bundle\BaseBundle;

class BreadcrumbWidgetBundle extends BaseBundle
{
    public function getName(): string
    {
        return 'breadcrumb-widget';
    }

    public function build(ContainerBuilder $containerBuilder)
    {
        $this->importServices($containerBuilder, __DIR__ . '/../resources/config/services/main.php');
    }
}
