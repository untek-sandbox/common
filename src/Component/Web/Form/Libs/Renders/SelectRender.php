<?php

namespace Untek\Component\Web\Form\Libs\Renders;

use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Untek\Component\Web\Html\Helpers\Html;

class SelectRender extends BaseInputRender
{

    public function tagName(): string
    {
        return 'select';
    }

    public function defaultOptions(): array
    {
        return [
            'class' => "custom-select"
        ];
    }

    public function render(): string
    {
        $optionsHtml = $this->renderOptions();
        $options = $this->options();
        return Html::tag($this->tagName(), $optionsHtml, $options);
    }

    private function renderOptions(): string
    {
        /** @var ChoiceView[] $choices */
        $choices = $this->getViewOptions()['choices'];
        $value = $this->getViewOptions()['value'];
        $optionsHtml = '';
        foreach ($choices as $choice) {
            $itemOptions = [
                'value' => $choice->value,
            ];
            if ($value == $choice->value) {
                $itemOptions['selected'] = 'selected';
            }
            $optionHtml = Html::tag('option', $choice->label, $itemOptions);
            $optionsHtml .= $optionHtml;
        }
        return $optionsHtml;
    }
}
