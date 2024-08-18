<?php

namespace Untek\Component\Web\WebApp\Subscribers;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class FindRouteSubscriber implements EventSubscriberInterface
{

    private RequestMatcherInterface $matcher;
    private RequestContext $context;
    private ?LoggerInterface $logger;
    private RequestStack $requestStack;

    public function __construct(
        RequestMatcherInterface $matcher,
        RequestStack $requestStack,
        RequestContext $context = null,
        LoggerInterface $logger = null
    ) {
        $this->matcher = $matcher;
        $this->context = $context ?? $matcher->getContext();
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 32]],
            KernelEvents::FINISH_REQUEST => [['onKernelFinishRequest', 0]],
//            KernelEvents::EXCEPTION => ['onKernelException', -64],
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $parameters = $this->matcher->matchRequest($request);
        if (is_array($parameters['_controller'])) {
            list($parameters['_controller'], $parameters['_action']) = $parameters['_controller'];
        }
        $parameters['_method'] = $request->getMethod();
        $request->attributes->add($parameters);
        unset($parameters['_route'], $parameters['_controller']);
        $request->attributes->set('_route_params', $parameters);
    }

    public function onKernelFinishRequest(FinishRequestEvent $event)
    {
        $this->setCurrentRequest($this->requestStack->getParentRequest());
    }

    private function setCurrentRequest(Request $request = null)
    {
        if (null !== $request) {
            try {
                $this->context->fromRequest($request);
            } catch (\UnexpectedValueException $e) {
                throw new BadRequestHttpException($e->getMessage(), $e, $e->getCode());
            }
        }
    }
}
