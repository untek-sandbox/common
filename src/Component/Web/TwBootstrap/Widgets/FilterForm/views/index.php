<?php

/**
 * @var HtmlRenderInterface $view
 * @var object $model
 * @var array $attributes
 */

use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Component\I18Next\Facades\I18Next;
use Untek\Component\Web\Html\Helpers\Html;

?>

<form id="filter" class="form-row mt-4 collapse filter" method="get" action="<?= '/' /*pathInfo*/ ?>">
    <?php foreach ($attributes as $attribute):
        $name = "filter[{$attribute['name']}]";
        $attribute['inputName'] = "filter[{$attribute['name']}]";
        $label = $attribute['label'];
        $type = $attribute['type'] ?? 'text';
        $attribute['value'] = PropertyHelper::getValue($model, $attribute['name']);
        ?>
        <div class="form-group col-lg-2 col-md-6">
            <?= Html::label('Title', $name, ['class' => 'sr-only']); ?>
            <?= $view->renderFile(__DIR__ . "/types/{$type}.php", $attribute) ?>
        </div>
    <?php endforeach; ?>
    <button type="submit" class="d-none"></button>
</form>

<div class="row align-items-center mt-4 mb-3">
    <div class="col-md-auto mb-md-0 mb-2">
        <span class="text-sm mr-2"><?= I18Next::t('layout', 'collection.filter.title') ?>:</span>
        <a class="btn btn-sm btn-link dropdown-toggle p-0" data-toggle="collapse" href="#filter" role="button"
           aria-expanded="false" aria-controls="filter">
            <?= I18Next::t('layout', 'collection.filter.collapse') ?>
        </a>
    </div>
</div>
