<?php

namespace Untek\Component\Web\WebApp\Libs\BundleLoaders;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Untek\Core\Bundle\Base\BaseLoader;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class SymfonyRoutesLoader extends BaseLoader
{

    private function createRoutingConfigurator($routes): RoutingConfigurator
    {
        $fileLocator = new FileLocator();
        $fileLoader = new PhpFileLoader($fileLocator);
        $routingConfigurator = new RoutingConfigurator($routes, $fileLoader, __FILE__, __FILE__);
        return $routingConfigurator;
    }

    public function loadAll(array $bundles): void
    {
        /** @var RouteCollection $routes */
        $routes = $this->container->get(RouteCollection::class);
        $this->getConfig($bundles, $routes);
    }

    protected function getConfig(array $bundles, RouteCollection $routes): RouteCollection {
        $routingConfigurator = $this->createRoutingConfigurator($routes);
        foreach ($bundles as $bundle) {
            $loadedConfig = $this->load($bundle);
            if ($loadedConfig) {
                foreach ($loadedConfig as $configFile) {
                    $closure = include $configFile;
                    call_user_func($closure, $routingConfigurator);
                }
            }
        }
        return $routes;
    }
}
