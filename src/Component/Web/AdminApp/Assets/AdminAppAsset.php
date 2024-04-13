<?php

namespace Untek\Component\Web\AdminApp\Assets;

use Untek\Component\Web\Asset\Base\BaseAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

class AdminAppAsset extends BaseAsset
{

    public function register(HtmlRenderInterface $htmlRender)
    {
        (new \Untek\Component\Web\Asset\Assets\Jquery3Asset())->register($htmlRender);
        (new \Untek\Component\Web\TwBootstrap\Assets\Bootstrap4Asset())->register($htmlRender);
        (new \Untek\Component\Web\AdminLte3\Assets\AdminLte3Asset())->register($htmlRender);
        (new \Untek\Component\Web\Asset\Assets\PopperAsset())->register($htmlRender);
        (new \Untek\Component\Web\Asset\Assets\Fontawesome5Asset())->register($htmlRender);
    }
}
