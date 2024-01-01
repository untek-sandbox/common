<?php

namespace Untek\Component\Web\Error\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;
use Untek\Component\Web\Error\Libs\CallAction;
use Untek\Component\Web\Error\Symfony4\Controllers\WebErrorController;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class ErrorHandleSubscriber implements EventSubscriberInterface
{

    private CallAction $callAction;
    private string $layout;
    private array $layoutParams = [];
    private HtmlRenderInterface $htmlRender;

    public function __construct(
        CallAction $callAction,
        HtmlRenderInterface $htmlRender
    ) {
        $this->callAction = $callAction;
        $this->htmlRender = $htmlRender;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    public function getLayoutParams(): array
    {
        return $this->layoutParams;
    }

    public function setLayoutParams(array $layoutParams): void
    {
        $this->layoutParams = $layoutParams;
    }

    public function addLayoutParam(string $name, $value): void
    {
        $this->layoutParams[$name] = $value;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $request = $event->getRequest()->duplicate();
        $response = $this->forgeResponse($request, $event->getThrowable());
        $event->setResponse($response);
        $event->stopPropagation();
    }

    protected function forgeResponse(Request $request, Throwable $e): Response
    {
        $request->attributes->set('_controller', WebErrorController::class);
        $request->attributes->set('_action', 'handleError');
        $arguments = [
            $request,
            $e,
        ];
        $response = $this->callAction->call($request, $arguments);
        $this->wrapContent($response);
        return $response;
    }

    private function wrapContent(Response $response): void
    {
        $params = $this->getLayoutParams();
        $params['content'] = $response->getContent();
        $content = $this->htmlRender->renderFile($this->layout, $params);
        $response->setContent($content);
    }
}
