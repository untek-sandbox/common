<?php

namespace Untek\Bundle\Notify\Infrastructure;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Kernel\Bundle\BaseBundle;

DeprecateHelper::hardThrow();

class ToastrWidgetBundle extends BaseBundle
{
    public function getName(): string
    {
        return 'toastr-widget';
    }

    public function build(ContainerBuilder $containerBuilder)
    {
        $this->importServices($containerBuilder, __DIR__ . '/DependencyInjection/Symfony/services/toastr.php');
    }
}
