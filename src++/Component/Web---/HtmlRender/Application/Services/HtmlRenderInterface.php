<?php

namespace Untek\Component\Web\HtmlRender\Application\Services;

interface HtmlRenderInterface
{
    public function getJs(): JsResourceInterface;

    public function getCss(): CssResourceInterface;

    public function renderFile(string $viewFile, array $params = []): string;
}
