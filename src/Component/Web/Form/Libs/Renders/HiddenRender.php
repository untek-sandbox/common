<?php

namespace Untek\Component\Web\Form\Libs\Renders;

class HiddenRender extends BaseInputRender
{

    public function defaultOptions(): array {
        return [
            'class'=>"form-control",
            'type' => 'hidden',
        ];
    }
}
