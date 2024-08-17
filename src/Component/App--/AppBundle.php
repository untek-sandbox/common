<?php

namespace Untek\Component\App;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class AppBundle extends AbstractBundle
{

    public function build(ContainerBuilder $container)
    {
        $fileLocator = new FileLocator(__DIR__);
        $loader = new PhpFileLoader($container, $fileLocator);
        $loader->load(__DIR__ . '/resources/config/services/main.php');
//        $loader->load(__DIR__ . '/../../../../../untek-sandbox/new-packages/src/Core/App/resources/main.php');
        $loader->load(__DIR__ . '/../../../../../untek-sandbox/new-packages/src/Core/Instance/resources/config/services/argument-resolver.php');
    }
}
