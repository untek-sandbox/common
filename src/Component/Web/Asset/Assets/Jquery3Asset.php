<?php

namespace Untek\Component\Web\Asset\Assets;

use Untek\Component\Web\Asset\Base\BaseAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

class Jquery3Asset extends BaseAsset
{
    public function __construct(string $baseUrl = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0')
    {
        $this->baseUrl = $baseUrl;
    }

    public function jsFiles(HtmlRenderInterface $htmlRender)
    {
            $htmlRender->getJs()->registerFile($this->baseUrl . '/jquery.min.js', [
//                'integrity' => 'sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==',
//                'crossorigin' => 'anonymous',
//                'referrerpolicy' => 'no-referrer',
            ]);
    }

    public function cssFiles(HtmlRenderInterface $htmlRender)
    {
        
    }
}
