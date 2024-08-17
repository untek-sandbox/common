<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Filter\Widgets;

use Untek\Component\Web\Html\Helpers\Html;
use Untek\Component\Web\Widget\Base\BaseWidget2;

class BaseFilterWidget extends BaseWidget2
{

    public $type;
    public $name;
    public $value;
    public $options = [
        'class' => 'form-control',
    ];

    public function run(): string
    {
        $name = 'filter[' . $this->name . ']';
        return Html::input($this->type, $name, $this->value, $this->options);
    }
}