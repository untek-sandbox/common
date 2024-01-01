<?php

namespace Untek\Component\Web\RequireJs\Assets;

use Untek\Component\Web\Asset\Base\BaseAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

class RjsAppAsset extends BaseAsset
{

    public function register(HtmlRenderInterface $htmlRender)
    {
        (new \Untek\Component\Web\Asset\Assets\Jquery3Asset())->cssFiles($htmlRender);
        (new \Untek\Component\Web\TwBootstrap\Assets\Bootstrap4Asset())->cssFiles($htmlRender);
        (new \Untek\Component\Web\Asset\Assets\PopperAsset())->cssFiles($htmlRender);
        (new \Untek\Component\Web\Asset\Assets\Fontawesome5Asset())->register($htmlRender);
    }
}
