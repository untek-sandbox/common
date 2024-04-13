<?php

namespace Untek\Component\Web\TwBootstrap\Assets;

use Untek\Component\Web\Asset\Base\BaseAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

class Bootstrap4Asset extends BaseAsset
{
    public function __construct(string $baseUrl = 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0')
    {
        $this->baseUrl = $baseUrl;
    }

    public function jsFiles(HtmlRenderInterface $htmlRender)
    {
            $htmlRender->getJs()->registerFile($this->baseUrl . '/js/bootstrap.min.js', [
//                'integrity' => 'sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl',
//                'crossorigin' => 'anonymous',
            ]);
    }

    public function cssFiles(HtmlRenderInterface $htmlRender)
    {
            $htmlRender->getCss()->registerFile($this->baseUrl . '/css/bootstrap.min.css', [
//                'integrity' => 'sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm',
//                'crossorigin' => 'anonymous',
            ]);
    }
}
