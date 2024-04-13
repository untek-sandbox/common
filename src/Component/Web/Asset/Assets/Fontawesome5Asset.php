<?php

namespace Untek\Component\Web\Asset\Assets;

use Untek\Component\Web\Asset\Base\BaseAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

class Fontawesome5Asset extends BaseAsset
{
    public function __construct(string $baseUrl = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3')
    {
        $this->baseUrl = $baseUrl;
    }

    public function cssFiles(HtmlRenderInterface $htmlRender)
    {
        $htmlRender->getCss()->registerFile(
            $this->baseUrl . '/css/all.min.css',
            /*[
                'integrity' => 'sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==',
                'crossorigin' => 'anonymous',
            ]*/
        );
    }
}
