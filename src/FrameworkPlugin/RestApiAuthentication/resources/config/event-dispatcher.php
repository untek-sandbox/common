<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Untek\FrameworkPlugin\RestApiAuthentication\Infrastructure\Subscribers\RestApiAuthenticationSubscriber;

//\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

return function (EventDispatcherInterface $eventDispatcher, ContainerInterface $container) {
    $webAuthenticationSubscriber = $container->get(RestApiAuthenticationSubscriber::class);
    $eventDispatcher->addSubscriber($webAuthenticationSubscriber);
};
