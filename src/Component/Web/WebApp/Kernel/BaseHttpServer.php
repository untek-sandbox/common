<?php

namespace Untek\Component\Web\WebApp\Kernel;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Untek\Core\App\Interfaces\AppInterface;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\EventDispatcher\Interfaces\EventDispatcherConfiguratorInterface;
use Untek\Component\Web\WebApp\Base\BaseWebApp;

DeprecateHelper::hardThrow();

abstract class BaseHttpServer implements TerminableInterface, HttpKernelInterface
{

    protected ContainerInterface $container;
    protected HttpKernelInterface $framework;
    protected array $contextClasses = [];

    abstract protected function getContext(Request $request): string;

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        $this->framework = $this->createKernelInstance($request);

        // actually execute the kernel, which turns the request into a response
        // by dispatching events, calling a controller, and returning the response
        return $this->framework->handle($request);
    }

    public function terminate(Request $request, Response $response)
    {
        // trigger the kernel.terminate event
        if ($this->framework instanceof TerminableInterface) {
            $this->framework->terminate($request, $response);
        }
    }

    protected function createKernelInstance(Request $request): HttpKernelInterface
    {
        $context = $this->getContext($request);
        $appClass = $this->getAppClassByContext($context);

//        $appClass = $this->getAppClass($request);
        $this->getContainerConfigurator()->singleton(AppInterface::class, $appClass);
        /** @var BaseWebApp $app */
        $app = $this->container->get(AppInterface::class);
        $app->setRequest($request);
        $app->setContext($context);
        $this->registerSubscribers();
        $app->init();
        return $this->getKernelInstance();
    }

    protected function getContextClasses(): array
    {
        return $this->contextClasses;
    }

    protected function getAppClassByContext(string $context): string
    {
        $classes = $this->getContextClasses();
        return $classes[$context];
    }

    protected function registerSubscribers(): void
    {
        $eventDispatcherConfigurator = $this->getEventDispatcherConfigurator();
        $this->configureEventDispatcher($eventDispatcherConfigurator);
    }

    protected function configureEventDispatcher(EventDispatcherConfiguratorInterface $eventDispatcherConfigurator): void
    {
    }

    protected function getKernelInstance(): HttpKernelInterface
    {
        /** @var HttpKernelInterface $framework */
        $framework = $this->container->get(HttpKernelInterface::class);
        return $framework;
    }

    protected function getEventDispatcherConfigurator(): EventDispatcherConfiguratorInterface
    {
        /** @var EventDispatcherConfiguratorInterface $eventDispatcherConfigurator */
        $eventDispatcherConfigurator = $this->container->get(EventDispatcherConfiguratorInterface::class);
        return $eventDispatcherConfigurator;
    }

    protected function getContainerConfigurator(): ContainerConfiguratorInterface
    {
        /** @var ContainerConfiguratorInterface $containerConfigurator */
        $containerConfigurator = $this->container->get(ContainerConfiguratorInterface::class);
        return $containerConfigurator;
    }
}
