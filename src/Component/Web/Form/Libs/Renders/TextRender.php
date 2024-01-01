<?php

namespace Untek\Component\Web\Form\Libs\Renders;

class TextRender extends BaseInputRender
{

    public function defaultOptions(): array {
        return [
            'class'=>"form-control"
        ];
    }
}
