<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Untek\FrameworkPlugin\HttpLayout\Infrastructure\Subscribers\SetLayoutSubscriber;

return function (EventDispatcherInterface $eventDispatcher, ContainerInterface $container) {
    $setLayoutSubscriber = $container->get(SetLayoutSubscriber::class);
    $eventDispatcher->addSubscriber($setLayoutSubscriber);
};
