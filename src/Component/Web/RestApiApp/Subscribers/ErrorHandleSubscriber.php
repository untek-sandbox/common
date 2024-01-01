<?php

namespace Untek\Component\Web\RestApiApp\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;
use Untek\Component\Web\Error\Libs\CallAction;
use Untek\Component\Web\RestApiApp\Symfony4\Controllers\ErrorController2;

class ErrorHandleSubscriber implements EventSubscriberInterface
{

    private string $controllerClass;
    private CallAction $callAction;
//    private string $layout;
//    private array $layoutParams = [];
//    private View $view;

    public function __construct(
        CallAction $callAction
//        View $view
    ) {
        $this->callAction = $callAction;
//        $this->view = $view;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function setControllerClass(string $controllerClass): void
    {
        $this->controllerClass = $controllerClass;
    }

    /*public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }*/

    /*public function getLayoutParams(): array
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
    }*/

    public function onKernelException(ExceptionEvent $event)
    {
        $request = $event->getRequest()->duplicate();
        $response = $this->forgeResponse($request, $event->getThrowable());
        $event->setResponse($response);
        $event->stopPropagation();
    }

    protected function forgeResponse(Request $request, Throwable $e): Response
    {
        $request->attributes->set('_controller', $this->controllerClass);
        $request->attributes->set('_action', 'handleError');
        $arguments = [
            $request,
            $e,
        ];
        $response = $this->callAction->call($request, $arguments);
//        $this->wrapContent($response);
        return $response;
    }

    /*private function wrapContent(Response $response): void
    {
        $params = $this->getLayoutParams();
        $params['content'] = $response->getContent();
        $content = $this->view->renderFile($this->layout, $params);
        $response->setContent($content);
    }*/
}
