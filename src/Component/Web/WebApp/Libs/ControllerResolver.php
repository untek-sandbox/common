<?php

namespace Untek\Component\Web\WebApp\Libs;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Container\Traits\ContainerAwareTrait;

DeprecateHelper::hardThrow();

class ControllerResolver implements ControllerResolverInterface
{

    use ContainerAwareTrait;

    public function __construct(ContainerInterface $container, private UrlMatcher $matcher)
    {
//        $this->matcher = $matcher;
        $this->setContainer($container);
    }

    public function getController(Request $request): callable|false
    {
        $controllerClass = $request->attributes->get('_controller');
        $actionName = $request->attributes->get('_action');
        $controllerInstance = $this->getContainer()->get($controllerClass);
        return [$controllerInstance, $actionName];
    }
}
