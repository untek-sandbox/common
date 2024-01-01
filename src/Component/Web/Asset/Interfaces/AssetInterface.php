<?php

namespace Untek\Component\Web\Asset\Interfaces;

use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

interface AssetInterface
{

    public function register(HtmlRenderInterface $htmlRender);
}
