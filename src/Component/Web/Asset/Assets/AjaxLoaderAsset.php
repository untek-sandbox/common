<?php

namespace Untek\Component\Web\Asset\Assets;

use Untek\Component\Web\Asset\Base\BaseAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

class AjaxLoaderAsset extends BaseAsset
{

    public function jsFiles(HtmlRenderInterface $htmlRender)
    {
        if (getenv('AJAX_ENABLE')) {
            $htmlRender->getJs()->registerFile('/assets/app/lib/ajax.js');
            $htmlRender->getJs()->registerFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js', [
                'integrity' => "sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww==",
                'crossorigin' => "anonymous",
                'referrerpolicy' => "no-referrer"
            ]);
            $htmlRender->getJs()->registerVar('ajaxLoaderStartTime', getenv('AJAX_LOADER_START_TIME') ?: null);
        }
    }

    public function cssFiles(HtmlRenderInterface $htmlRender)
    {

    }
}
