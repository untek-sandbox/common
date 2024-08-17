<?php

namespace Untek\Component\Web\HtmlRender\Infrastructure\Services;

use Untek\Component\Web\Html\Helpers\Html;
use Untek\Component\Web\HtmlRender\Application\Services\CssResourceInterface;

class CssResource extends BaseResource implements CssResourceInterface
{
    public function render(): string
    {
        $cssCode = '';
        foreach ($this->getFiles() as $item) {
            $options = $item['options'];
            $options['rel'] = 'stylesheet';
            $options['href'] = $item['file'];
            if (getenv('ASSET_FORCE_RELOAD') ?: false) {
                $options['href'] .= '?timestamp=' . time();
            }
            $cssCode .= Html::tag('link', '', $options);
        }
        $this->resetFiles();
        $cssCode .= "<style>{$this->getCode()}</style>";
        $this->resetCode();
        return $cssCode;
    }
}
