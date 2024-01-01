<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr;

use Untek\Component\Web\Asset\Base\BaseAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

class ToastrAsset extends BaseAsset
{
    public function __construct(string $baseUrl = 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest')
    {
        $this->baseUrl = $baseUrl;
    }

    public function jsFiles(HtmlRenderInterface $htmlRender)
    {
        $htmlRender->getJs()->registerFile($this->baseUrl . '/js/toastr.min.js');
    }

    public function cssFiles(HtmlRenderInterface $htmlRender)
    {
        $htmlRender->getCss()->registerFile($this->baseUrl . '/css/toastr.min.css');
    }
}
