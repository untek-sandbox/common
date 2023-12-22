<?php

namespace Untek\Framework\Socket\Domain\Apps\Base;

use Psr\Container\ContainerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Untek\Core\App\Base\BaseApp;
use Untek\Core\App\Libs\ZnCore;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\ConfigManager\Interfaces\ConfigManagerInterface;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Framework\Console\Domain\Subscribers\ConsoleDetectTestEnvSubscriber;
use Untek\Framework\Socket\Domain\Libs\SocketDaemon;

//DeprecateHelper::hardThrow();

abstract class BaseWebSocketApp extends BaseApp
{

    private $configManager;

    public function __construct(
        ContainerInterface $container,
        EventDispatcherInterface $dispatcher,
        ZnCore $znCore,
        ContainerConfiguratorInterface $containerConfigurator,
        ConfigManagerInterface $configManager
    )
    {
        parent::__construct($container, $dispatcher, $znCore, $containerConfigurator);
        $this->configManager = $configManager;
    }

    public function appName(): string
    {
        return 'webSocket';
    }

    public function subscribes(): array
    {
        return [
            ConsoleDetectTestEnvSubscriber::class,
        ];
    }

    public function import(): array
    {
        return ['i18next', 'container', 'entityManager', 'eventDispatcher', 'console', 'migration', 'rbac', 'symfonyRpc', 'telegramRoutes'];
    }

    protected function configContainer(ContainerConfiguratorInterface $containerConfigurator): void
    {
        $containerConfigurator->singleton(SocketDaemon::class, SocketDaemon::class);
    }
}
