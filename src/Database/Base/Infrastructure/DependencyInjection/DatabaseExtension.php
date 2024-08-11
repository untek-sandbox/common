<?php

namespace Untek\Database\Base\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class DatabaseExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        foreach ($configs as $config) {
            if(isset($config['migration']['config_path'])) {
                $container->setParameter('database.migration.config_path', $config['migration']['config_path']);
            }
            if(isset($config['seed']['path'])) {
                $container->setParameter('database.seed.path', $config['seed']['path']);
            }
        }

        $fileLocator = new FileLocator(__DIR__);
        $loader = new PhpFileLoader($container, $fileLocator);
        $loader->load(__DIR__ . '/../../../Doctrine/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../../../Eloquent/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../../../Seed/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../../../Migration/resources/config/services/main.php');

    }
}