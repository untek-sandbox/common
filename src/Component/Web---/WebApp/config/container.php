<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Untek\Component\Web\WebApp\Libs\ControllerResolver;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

return [
    'singletons' => [
        ControllerResolverInterface::class => ControllerResolver::class,
        ArgumentResolverInterface::class => ArgumentResolver::class,
        UrlGeneratorInterface::class => UrlGenerator::class,
//        TokenStorageInterface::class => SessionTokenStorage::class,
        /*TokenStorageInterface::class => function (ContainerInterface $container) {
//            $session = $container->get(SessionInterface::class);
            $requestStack = $container->get(RequestStack::class);
            return new SessionTokenStorage($requestStack);
        },*/
        SessionInterface::class => Session::class,
    ],
];
