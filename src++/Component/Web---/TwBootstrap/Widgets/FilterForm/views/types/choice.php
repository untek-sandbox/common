<?php

/**
 * @var string $inputName
 * @var string $label
 * @var $value
 * @var array $options
 */


use Untek\Component\Web\Html\Helpers\Html; ?>

<?= Html::dropDownList($inputName, $value, $options, [
    'class' => 'form-control select2',
]); ?>
