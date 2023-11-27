<?php

namespace Untek\FrameworkPlugin\RestApiCors\Infrastructure\Subscribers;

use Fruitcake\Cors\CorsService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Untek\Component\Http\Enums\HttpStatusCodeEnum;

class CorsSubscriber implements EventSubscriberInterface
{

    private $corsService;

    public function __construct(CorsService $corsService)
    {
        $this->corsService = $corsService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        if ($this->corsService->isPreflightRequest($request)) {
            $response->setStatusCode(HttpStatusCodeEnum::NO_CONTENT);
            $this->corsService->addPreflightRequestHeaders($response, $request);
        } else {
            $this->corsService->addActualRequestHeaders($response, $request);
        }
    }
}
