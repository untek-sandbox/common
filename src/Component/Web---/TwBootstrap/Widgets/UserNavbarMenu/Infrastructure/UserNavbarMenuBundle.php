<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\UserNavbarMenu\Infrastructure;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Core\Kernel\Bundle\BaseBundle;

class UserNavbarMenuBundle extends BaseBundle
{
    public function getName(): string
    {
        return 'user-navbar-menu';
    }

    public function build(ContainerBuilder $containerBuilder)
    {
        $this->importServices($containerBuilder, __DIR__ . '/../resources/config/services/main.php');
    }

    public function boot(ContainerInterface $container): void
    {
//        $this->configureFromPhpFile(__DIR__ . '/../resources/config/event-dispatcher.php', $container);
    }
}
