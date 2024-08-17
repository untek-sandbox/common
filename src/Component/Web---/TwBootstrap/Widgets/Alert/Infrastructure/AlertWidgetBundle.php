<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Alert\Infrastructure;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Core\Kernel\Bundle\BaseBundle;

class AlertWidgetBundle extends BaseBundle
{
    public function getName(): string
    {
        return 'alert-widget';
    }

    public function build(ContainerBuilder $containerBuilder)
    {
        $this->importServices($containerBuilder, __DIR__ . '/../resources/config/services/main.php');
    }
}
