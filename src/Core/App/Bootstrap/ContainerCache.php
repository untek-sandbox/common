<?php

namespace Untek\Core\App\Bootstrap;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Filesystem\Filesystem;

class ContainerCache
{

    public function __construct(
        private ?string $cacheDirectory = null,
    )
    {
    }

    public function has(): bool
    {
        return file_exists($this->getCachedContainerFile());
    }

    public function load(): ContainerInterface
    {
        require_once $this->getCachedContainerFile();
        return new \ProjectServiceContainer();
    }

    public function compileContainer(string $context, ContainerBuilder $containerBuilder): void
    {
        $dumper = new PhpDumper($containerBuilder);
        $cacheFile = $this->getCachedContainerFile();
        (new Filesystem())->dumpFile($cacheFile, $dumper->dump());
    }

    private function getCachedContainerFile(): string
    {
        return $this->cacheDirectory . '/container/container.php';
    }
}
