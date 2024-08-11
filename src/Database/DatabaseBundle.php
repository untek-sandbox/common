<?php

namespace Untek\Database;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Untek\Model\Cqrs\Application\Abstract\CqrsHandlerInterface;
use Untek\Model\Cqrs\Infrastructure\DependencyInjection\CqrsExtension;

class DatabaseBundle extends AbstractBundle
{

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(__DIR__ . '/../Database/Doctrine/resources/config/services/main.php');
        $container->import(__DIR__ . '/../Database/Eloquent/resources/config/services/main.php');
        $container->import(__DIR__ . '/../Database/Seed/resources/config/services/');
        $container->import(__DIR__ . '/../Database/Migration/resources/config/services/');
    }
}