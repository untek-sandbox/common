<?php

namespace Untek\FrameworkPlugin\HttpLayout\Infrastructure\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Untek\Component\Http\Enums\HttpStatusCodeEnum;
use Untek\Component\Web\TwBootstrap\Widgets\Alert\AlertWidget;
use Untek\Component\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

class SetLayoutSubscriber implements EventSubscriberInterface
{
    private ?string $layout;

    private array $layoutParams = [];

    public function __construct(private HtmlRenderInterface $htmlRender)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $isAjax = $event->getRequest()->isXmlHttpRequest();

        $isWebResponse = get_class($response) == Response::class;
        $isOk = $response->getStatusCode() === HttpStatusCodeEnum::OK;
        $hasDisposition = $response->headers->has('content-disposition');

        if ($isAjax) {
            $jsonResponse = new JsonResponse(
                [
                    //'title' => 'title',
                    'url' => $event->getRequest()->getRequestUri(),
                    'content' => [
                        'content' => $response->getContent(),
                        'breadcrumb' => BreadcrumbWidget::widget(),
                        'alert' => AlertWidget::widget(),
                        'runtime' => round(microtime(true) - $_SERVER['MICRO_TIME'], 3),
                    ],
                ]
            );
            $event->setResponse($jsonResponse);
        } elseif ($isWebResponse /*&& $isOk*/ && !$hasDisposition) {
            $this->wrapContent($response);
        }
    }

    /*public function getLayout(): ?string
    {
        return $this->layout;
    }*/

    public function setLayout(?string $layout): void
    {
        $this->layout = $layout;
    }

    /*public function getLayoutParams(): array
    {
        return $this->layoutParams;
    }*/

    /*public function setLayoutParams(array $layoutParams): void
    {
        $this->layoutParams = $layoutParams;
    }

    public function addLayoutParam(string $name, $value): void
    {
        $this->layoutParams[$name] = $value;
    }*/

    private function wrapContent(Response $response): void
    {
        $params = $this->layoutParams;
        $params['content'] = $response->getContent();
        $content = $this->htmlRender->renderFile($this->layout, $params);
        $response->setContent($content);
    }
}
