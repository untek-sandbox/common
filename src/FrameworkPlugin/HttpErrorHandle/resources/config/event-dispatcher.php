<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Untek\FrameworkPlugin\HttpErrorHandle\Presentation\Http\Site\Controllers\HttpErrorController;
use Untek\FrameworkPlugin\HttpErrorHandle\Infrastructure\Subscribers\HttpHandleSubscriber;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

return function (EventDispatcherInterface $eventDispatcher, ContainerInterface $container) {
    /** @var HttpHandleSubscriber $restApiHandleSubscriber */
    $restApiHandleSubscriber = $container->get(HttpHandleSubscriber::class);
    $restApiHandleSubscriber->setRestApiErrorControllerClass(HttpErrorController::class);
    $eventDispatcher->addSubscriber($restApiHandleSubscriber);
};
