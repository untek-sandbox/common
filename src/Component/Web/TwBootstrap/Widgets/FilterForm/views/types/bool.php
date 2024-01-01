<?php

/**
 * @var string $inputName
 * @var string $label
 * @var $value
 */

use Untek\Component\I18Next\Facades\I18Next;
use Untek\Component\Web\Html\Helpers\Html;

$options = [
    null => $label,
    '0' => I18Next::t('core', 'main.no'),
    '1' => I18Next::t('core', 'main.yes'),
];

?>

<?= Html::dropDownList($inputName, $value, $options, [
    'class' => 'form-control select2',
]); ?>
