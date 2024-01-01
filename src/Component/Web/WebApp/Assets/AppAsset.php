<?php

namespace Untek\Component\Web\WebApp\Assets;

use Untek\Component\Web\Asset\Base\BaseAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Core\Code\Helpers\DeprecateHelper;

//DeprecateHelper::hardThrow();

class AppAsset extends BaseAsset
{

    public function register(HtmlRenderInterface $htmlRender)
    {
        (new \Untek\Component\Web\Asset\Assets\Jquery3Asset())->register($htmlRender);
        (new \Untek\Component\Web\Asset\Assets\AjaxLoaderAsset())->register($htmlRender);
        (new \Untek\Component\Web\TwBootstrap\Assets\Bootstrap4Asset())->register($htmlRender);
        (new \Untek\Component\Web\Asset\Assets\PopperAsset())->register($htmlRender);
        (new \Untek\Component\Web\Asset\Assets\Fontawesome5Asset())->register($htmlRender);
    }
}
