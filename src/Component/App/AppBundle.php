<?php

namespace Untek\Component\App;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Untek\Model\Cqrs\Application\Abstract\CqrsHandlerInterface;

class AppBundle extends AbstractBundle
{

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(__DIR__ . '/resources/config/services/main.php');
        $container->import(__DIR__ . '/../../../../../untek-core/app/resources/main.php');
        $container->import(__DIR__ . '/../../../../../untek-core/instance/src/resources/config/services/argument-resolver.php');
    }
}
