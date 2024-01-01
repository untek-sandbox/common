<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Untek\FrameworkPlugin\HttpAuthentication\Infrastructure\Subscribers\WebAuthenticationSubscriber;

return function (EventDispatcherInterface $eventDispatcher, ContainerInterface $container) {
    $webAuthenticationSubscriber = $container->get(WebAuthenticationSubscriber::class);
    $eventDispatcher->addSubscriber($webAuthenticationSubscriber);
};
