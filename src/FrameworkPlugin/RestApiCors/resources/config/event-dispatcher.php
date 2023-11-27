<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Untek\FrameworkPlugin\RestApiCors\Infrastructure\Subscribers\CorsSubscriber;

return function (EventDispatcherInterface $eventDispatcher, ContainerInterface $container) {
    $corsSubscriber = $container->get(CorsSubscriber::class); // Обработка CORS-запросов
    $eventDispatcher->addSubscriber($corsSubscriber);
};
