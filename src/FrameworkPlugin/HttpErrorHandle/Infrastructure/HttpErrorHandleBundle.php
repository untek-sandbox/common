<?php

namespace Untek\FrameworkPlugin\HttpErrorHandle\Infrastructure;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Core\Kernel\Bundle\BaseBundle;

class HttpErrorHandleBundle extends BaseBundle
{
    public function getName(): string
    {
        return 'http-error-handle';
    }

    public function build(ContainerBuilder $containerBuilder)
    {
        $this->importServices($containerBuilder, __DIR__ . '/../resources/config/services/main.php');
    }

    public function boot(ContainerInterface $container): void
    {
        $this->configureFromPhpFile(__DIR__ . '/../resources/config/event-dispatcher.php', $container);
    }
}
