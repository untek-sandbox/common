<?php

namespace Untek\Component\Web\AdminLte3\Assets;

use Untek\Component\Web\Asset\Base\BaseAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

class AdminLte3Asset extends BaseAsset
{

    public function jsFiles(HtmlRenderInterface $htmlRender)
    {
        $htmlRender->getJs()->registerFile('https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js', [
            'integrity' => 'sha512-AJUWwfMxFuQLv1iPZOTZX0N/jTCIrLxyZjTRKQostNU71MzZTEPHjajSK20Kj1TwJELpP7gl+ShXw5brpnKwEg==',
            'crossorigin' => 'anonymous',
        ]);
    }

    public function cssFiles(HtmlRenderInterface $htmlRender)
    {
        $htmlRender->getCss()->registerFile('https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css', [
            'integrity' => 'sha512-mxrUXSjrxl8vm5GwafxcqTrEwO1/oBNU25l20GODsysHReZo4uhVISzAKzaABH6/tTfAxZrY2FprmeAP5UZY8A==',
            'crossorigin' => 'anonymous',
        ]);
    }
}
