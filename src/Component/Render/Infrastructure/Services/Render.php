<?php

namespace Untek\Component\Render\Infrastructure\Services;

use Untek\Component\Render\Infrastructure\Helpers\RenderHelper;

/**
 * Class Render
 * @todo kill old class
 * @see \Untek\Component\Web\HtmlRender\Infrastructure\Services\HtmlRender
 */
class Render
{

    public function renderFile(string $viewFile, array $params = []): string
    {
        $out = '';
        ob_start();
        ob_implicit_flush(false);
        try {
            $this->includeRender($viewFile, $params);
            // after render wirte in $out
        } catch (\Exception $e) {
            // close the output buffer opened above if it has not been closed already
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }
        return ob_get_clean() . $out;
    }

    protected function includeRender(string $__viewFile, array $__params = []): void
    {
        RenderHelper::includeRender($__viewFile, $__params + ['view' => $this]);
    }
}
