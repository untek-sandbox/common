<?php

namespace Untek\Component\Web\HtmlRender\Infrastructure\Services;

use Untek\Component\Web\Html\Helpers\Html;
use Untek\Component\Web\HtmlRender\Application\Services\JsResourceInterface;
use Untek\Core\Arr\Helpers\ArrayHelper;

class JsResource extends BaseResource implements JsResourceInterface
{
    public function render(): string
    {
        $jsCode = '';
        foreach ($this->getFiles() as $item) {
            $options = $item['options'];
            $options['src'] = $item['file'];
            if (getenv('ASSET_FORCE_RELOAD') ?: false) {
                $options['src'] .= '?timestamp=' . time();
            }
            $jsCode .= Html::tag('script', '', $options);
        }
        $this->resetFiles();
        $jsCode .= "
            <script>
                jQuery(function ($) {
                    {$this->getCode()}
                });
            </script>";
        $this->resetCode();
        return $jsCode;
    }

    public function registerVar(string $name, $value): void
    {
        if (is_object($value)) {
//            throw new \InvalidArgumentException("Object not supported!");
            $value = ArrayHelper::toArray($value);
        }
        $json = json_encode($value);
        $code = "$name = " . $json . ";";
        $this->registerCode($code);
    }
}
