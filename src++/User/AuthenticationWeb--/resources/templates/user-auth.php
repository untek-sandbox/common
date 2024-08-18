<?php

/**
 * @var $formRender \Untek\Component\Web\Form\Libs\FormRender
 * @var string $content
 */

?>

<?= $formRender->errors() ?>

<?= $formRender->beginFrom() ?>

<div class="form-group field-form-login required has-error">
    <?= $formRender->label('login') ?>
    <?= $formRender->input('login', 'text') ?>
    <?= $formRender->hint('login') ?>
</div>
<div class="form-group field-form-password required">
    <?= $formRender->label('password') ?>
    <?= $formRender->input('password', 'password', [
        'autocomplete' => 'off',
    ]) ?>
    <?= $formRender->hint('password') ?>
</div>
<div class="form-group field-form-rememberme">
    <div class="checkbox">
        <?= $formRender->input('rememberMe', 'checkbox') ?>
        <?= $formRender->label('rememberMe') ?>
        <?= $formRender->hint('rememberMe') ?>
    </div>
</div>
<div class="form-group">
    <?= $formRender->input('save', 'submit') ?>
</div>

<?= $formRender->endFrom() ?>
