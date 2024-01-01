<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\FilterForm;

use Untek\Component\Web\Widget\Base\BaseWidget2;

class FilterWidget extends BaseWidget2
{

    public $attributes = [];
    public $model = null;

    public function run(): string
    {
        return $this->render('index', [
            'model' => $this->model,
            'attributes' => $this->attributes
        ]);
    }
}

/*

# Example

$filterAttributes = [
    [
        'label' => I18Next::t('library', 'book.attribute.title'),
        'name' => 'title',
        'type' => ElementTypeEnum::TEXT,
    ],
    [
        'label' => I18Next::t('library', 'book.attribute.publishedAt'),
        'name' => 'publishedAt',
    ],
    [
        'label' => I18Next::t('library', 'book.attribute.isActual'),
        'name' => 'isActual',
        'type' => ElementTypeEnum::BOOLEAN,
    ],
    [
        'label' => I18Next::t('library', 'book.attribute.language'),
        'name' => 'language',
        'type' => ElementTypeEnum::CHOICE,
        'options' => $filterModel->languageOptions(),
    ],
];

FilterWidget::widget([
    'attributes' => $filterAttributes,
    'model' => $filterModel,
]);

 */
