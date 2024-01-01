<?php

namespace Untek\Component\Web\WebApp\Base;

use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RouteCollection;
use Untek\Core\App\Base\BaseApp;
use Untek\Core\App\Subscribers\PhpErrorSubscriber;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\EventDispatcher\Interfaces\EventDispatcherConfiguratorInterface;
use Untek\Component\Web\HtmlRender\Infrastructure\Services\HtmlRender;
use Untek\Component\Web\WebApp\Subscribers\FindRouteSubscriber;
use Untek\Component\Web\WebApp\Subscribers\WebDetectTestEnvSubscriber;
use Untek\Component\Web\WebApp\Subscribers\WebFirewallSubscriber;

DeprecateHelper::hardThrow();

abstract class BaseWebApp extends BaseApp
{

    public function appName(): string
    {
        return 'web';
    }

    public function subscribes(): array
    {
        return [
            WebDetectTestEnvSubscriber::class,
            PhpErrorSubscriber::class,
        ];
    }

    public function import(): array
    {
        return ['i18next', 'container', 'entityManager', 'eventDispatcher', 'rbac', 'symfonyWeb'];
    }

    protected function configDispatcher(EventDispatcherConfiguratorInterface $configurator): void
    {
        $configurator->addSubscriber(FindRouteSubscriber::class);
        $configurator->addSubscriber(WebFirewallSubscriber::class);
    }

    protected function configContainer(ContainerConfiguratorInterface $containerConfigurator): void
    {
        $containerConfigurator->singleton(HttpKernelInterface::class, HttpKernel::class);
        $containerConfigurator->bind(ErrorRendererInterface::class, HtmlErrorRenderer::class);
        $containerConfigurator->singleton(HtmlRender::class, HtmlRender::class);
        $containerConfigurator->singleton(RouteCollection::class, RouteCollection::class);
        $containerConfigurator->singleton(RequestStack::class, RequestStack::class);
        $containerConfigurator->singleton(UrlMatcherInterface::class, UrlMatcher::class);
        $containerConfigurator->singleton(RequestMatcherInterface::class, UrlMatcher::class);
//        $containerConfigurator->singleton('error_controller', ErrorController::class);
    }
}
