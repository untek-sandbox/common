<?php

namespace Untek\Component\Cqrs;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Untek\Component\Cqrs\Application\Abstract\CqrsHandlerInterface;
use Untek\Component\Cqrs\Infrastructure\DependencyInjection\CqrsExtension;
use Untek\Component\Cqrs\Infrastructure\DependencyInjection\CqrsPass;

class CqrsBundle extends AbstractBundle
{

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CqrsPass());

        $fileLocator = new FileLocator(__DIR__);
        $loader = new PhpFileLoader($container, $fileLocator);
        $loader->load(__DIR__ . '/resources/config/services/main.php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->registerForAutoconfiguration(CqrsHandlerInterface::class)
            ->addTag('cqrs.handler');
    }
}
