<?php

namespace Untek\Core\App\Bootstrap;

use Exception;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Core\Kernel\Kernel\BaseKernel;

abstract class AbstractAppKernel extends BaseKernel
{

    protected ConfigDirectory $configDirectory;
    protected string $context;
    protected string $environment;
    protected string $cacheDirectory;
    protected bool $isImportLocalConfig = false;
    protected bool $isTest;
    protected bool $booted = false;

    public function __construct(
        ConfigDirectory $configDirectory,
        string $context,
        string $environment,
        string $cacheDirectory,
        bool $isImportLocalConfig = false,
        bool $isDebug = false,
        bool $isTest = false,
    )
    {
        if (!$this->environment = $environment) {
            throw new InvalidArgumentException(
                sprintf('Invalid environment provided to "%s": the environment cannot be empty.', get_debug_type($this))
            );
        }
        $this->configDirectory = $configDirectory;
        $this->context = $context;
        $this->debug = $isDebug;
        $this->isTest = $isTest;
        $this->cacheDirectory = $cacheDirectory;
        $this->isImportLocalConfig = $isImportLocalConfig;
    }

    public function boot(): void
    {
        if (true === $this->booted) {
            throw new Exception('the kernel is already initialized.');
        }
        $this->initializeDebug();
        $this->initializeShellVerbosity();
        $this->initializeContainer();

        $this->booted = true;
    }

    protected function build(ContainerBuilder $container): void
    {
        
    }

    /**
     * Initializes the service container.
     *
     * The built version of the service container is used when fresh, otherwise the
     * container is built.
     *
     * @return void
     */
    protected function initializeContainer(): void
    {
        $containerCache = new ContainerCache($this->getCacheDirectory($this->context));
        $hasCache = $containerCache->has();
        $isProd = $this->environment == 'prod';
        if($isProd && $hasCache) {
            $containerBuilder = $containerCache->load();
        } else {
            $containerBuilder = $this->createContainerInstance();
            $containerBuilder->compile();
            if($isProd) {
                $containerCache->compileContainer($this->context, $containerBuilder);
            }
        }

        ContainerHelper::setContainer($containerBuilder);
        $this->setContainer($containerBuilder);
    }

    protected function createContainerInstance(): ContainerBuilder
    {
        $containerBuilder = ContainerFactory::create();
        $this->build($containerBuilder);
        $containerConfigLoader = new ContainerConfigLoader($this->configDirectory, $this->isImportLocalConfig);
        $containerConfigLoader->load($containerBuilder, $this->context);
        return $containerBuilder;
    }

    protected function getCacheDirectory(string $context = null): string
    {
        if ($context) {
            return rtrim($this->cacheDirectory, '/');
        }
        return $this->cacheDirectory;
    }
}
