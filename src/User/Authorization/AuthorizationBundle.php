<?php

namespace Untek\User\Authorization;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Untek\Model\Cqrs\Application\Abstract\CqrsHandlerInterface;

class AuthorizationBundle extends AbstractBundle
{

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(__DIR__ . '/resources/config/services/main.php');
    }
}
