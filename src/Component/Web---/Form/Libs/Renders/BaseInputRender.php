<?php

namespace Untek\Component\Web\Form\Libs\Renders;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Component\Web\Html\Helpers\Html;

abstract class BaseInputRender extends BaseRender
{
    
    protected $attributeName;

    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    public function setAttributeName(string $attributeName): void
    {
        $this->attributeName = $attributeName;
    }

    protected function getView()
    {
        return $this->getFormView()->children[$this->attributeName];
    }

    public function defaultOptions(): array {
        return [
            'class'=>"form-control"
        ];
    }

    public function tagName(): string {
        return 'input';
    }

    public function allowOptions(): array {
        return [
            "value",
            "id",
            'type',
//    "name",
            "disabled",
            "multipart",
           // "required",
        ];
    }
    
    public function render(): string
    {
        $options = $this->options();
        return Html::tag($this->tagName(), '', $options);
    }

    protected function getViewOptions(): array {
        return $this->getView()->vars;
    }

    protected function getViewOption($name) {
        return $this->getView()->vars[$name];
    }

    protected function options(): array {
        $inputVars = $this->getViewOptions();
        $options = $this->defaultOptions();
        $options = ArrayHelper::merge($options, $this->filterOptions($inputVars));
        $options = ArrayHelper::merge($options, $this->getOptions());
        return $options;
    }

    protected function filterOptions(array $options): array
    {
        $options1 = ArrayHelper::extractByKeys($options, $this->allowOptions());
        $options1['name'] = $options['full_name'];
        return $options1;
    }
}
