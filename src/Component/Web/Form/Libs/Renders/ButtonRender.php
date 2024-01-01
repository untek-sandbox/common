<?php

namespace Untek\Component\Web\Form\Libs\Renders;

use Untek\Component\Web\Html\Helpers\Html;

class ButtonRender extends BaseInputRender
{

    public function defaultOptions(): array {
        return [
            'class' => "btn btn-primary",
        ];
    }

    public function render(): string
    {
        $options = $this->options();
        return Html::submitButton($this->getViewOption('label'), $options);
    }
}
