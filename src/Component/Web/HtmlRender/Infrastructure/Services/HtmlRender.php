<?php

namespace Untek\Component\Web\HtmlRender\Infrastructure\Services;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Component\Web\HtmlRender\Application\Services\CssResourceInterface;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Component\Web\HtmlRender\Application\Services\JsResourceInterface;
use Untek\Component\Web\HtmlRender\Infrastructure\Helpers\RenderHelper;

class HtmlRender implements HtmlRenderInterface
{

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private JsResourceInterface $js,
        private CssResourceInterface $css
    )
    {
    }

    public function getJs(): JsResourceInterface
    {
        return $this->js;
    }

    public function getCss(): CssResourceInterface
    {
        return $this->css;
    }

    public function renderFile(string $viewFile, array $params = []): string
    {
        $out = '';
        ob_start();
        ob_implicit_flush(false);
        try {
            $this->includeRender($viewFile, $params);
            // after render wirte in $out
        } catch (\Exception $e) {
            // close the output buffer opened above if it has not been closed already
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }
        return ob_get_clean() . $out;
    }

    protected function includeRender(string $__viewFile, array $__params = []): void
    {
        RenderHelper::includeRender($__viewFile, $__params + ['view' => $this]);
    }
}
