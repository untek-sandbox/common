<?php

namespace Untek\Core\App\Bootstrap;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainerConfigLoader
{

    public function __construct(
        protected ConfigDirectory $configDirectory,
        protected bool $importLocalConfig,
    )
    {
    }

    public function load(ContainerBuilder $containerBuilder, string $context): void
    {
        $this->loadContainer($containerBuilder, $context);
    }

    private function loadContainer(ContainerBuilder $containerBuilder, string $context): void
    {
        $this->loadContainerBuilder($containerBuilder);
        if ($this->importLocalConfig) {
            $this->loadContainerBuilder($containerBuilder, null, 'container.local.php', true);
        }
        $this->loadContainerBuilder($containerBuilder, $context);
        if ($this->importLocalConfig) {
            $this->loadContainerBuilder($containerBuilder, $context, 'container.local.php', true);
        }
    }

    private function loadContainerBuilder(ContainerBuilder $containerBuilder, ?string $context = null, string $fileName = 'container.php', bool $hideErrors = false): void
    {
        $fileName = $this->getConfigDirectory($context) . '/' . $fileName;
        $this->includeContainerConfig($containerBuilder, $fileName, $hideErrors);
    }

    private function includeContainerConfig(ContainerBuilder $containerBuilder, string $fileName, bool $hideErrors): void
    {
        if ($hideErrors) {
            @include $fileName;
        } else {
            include $fileName;
        }
    }

    private function getConfigDirectory(string $context = null): string
    {
        return $this->configDirectory->getConfigDirectory($context);
    }
}