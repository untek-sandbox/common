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
//        $config = $this->mergeConfigs($configs);

        $configuration = new DatabaseConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        if (isset($config['migration']['config_path'])) {
            $container->setParameter('database.migration.config_path', $config['migration']['config_path']);
        }
        if (isset($config['seed']['path'])) {
            $container->setParameter('database.seed.path', $config['seed']['path']);
        }

        $fileLocator = new FileLocator(__DIR__);
        $loader = new PhpFileLoader($container, $fileLocator);
        $loader->load(__DIR__ . '/../../../Doctrine/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../../../Eloquent/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../../../Seed/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../../../Migration/resources/config/services/main.php');
    }

    private function mergeConfigs(array $configs): array
    {
        $config = [];
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }
        return $config;
    }
}