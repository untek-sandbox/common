<?php

namespace Untek\Component\Web\Asset\Assets;

use Untek\Component\Web\Asset\Base\BaseAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

class PopperAsset extends BaseAsset
{
    public function __construct(string $baseUrl = 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd')
    {
        $this->baseUrl = $baseUrl;
    }

    public function jsFiles(HtmlRenderInterface $htmlRender)
    {
            $htmlRender->getJs()->registerFile($this->baseUrl . '/popper.min.js', [
//                'integrity' => 'sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q',
//                'crossorigin' => 'anonymous',
            ]);
    }

    public function cssFiles(HtmlRenderInterface $htmlRender)
    {

    }
}
