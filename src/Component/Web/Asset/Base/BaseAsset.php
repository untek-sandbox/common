<?php

namespace Untek\Component\Web\Asset\Base;

use Untek\Component\Web\Asset\Interfaces\AssetInterface;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

abstract class BaseAsset implements AssetInterface
{
    protected string $baseUrl;

    public function jsFiles(HtmlRenderInterface $htmlRender)
    {

    }

    public function cssFiles(HtmlRenderInterface $htmlRender)
    {

    }

    public function jsCode(HtmlRenderInterface $htmlRender)
    {

    }

    public function cssCode(HtmlRenderInterface $htmlRender)
    {

    }

    public function register(HtmlRenderInterface $htmlRender)
    {
        $this->jsFiles($htmlRender);
        $this->cssFiles($htmlRender);
        $this->jsCode($htmlRender);
        $this->cssCode($htmlRender);
    }
}
