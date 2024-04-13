<?php

/**
 * @var HtmlRenderInterface $view
 * @var Enumerable $collection
 * @var DataProvider $dataProvider
 * @var AttributeEntity[] $attributes
 * @var string $tableClass
 * @var string $baseUrl
 * @var array $queryParams
 * @var FormatEncoder $formatter
 * @var object $filterModel
 * @var bool $showStatistic
 */

use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Model\DataProvider\Libs\DataProvider;
use Untek\Component\I18Next\Facades\I18Next;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Libs\FormatEncoder;
use Untek\Component\Web\TwBootstrap\Widgets\Pagination\PaginationWidget;

if (!isset($collection)) {
    $collection = $dataProvider->getCollection();
}

?>

<table class="<?= $tableClass ?>">
    <thead>
    <?= $view->renderFile(
        __DIR__ . '/head.php', [
        'attributes' => $attributes,
        'baseUrl' => $baseUrl,
        'queryParams' => $queryParams,
    ]) ?>
    <?php if ($filterModel): ?>
        <?= $view->renderFile(
            __DIR__ . '/filter.php', [
            'attributes' => $attributes,
            'filterModel' => $filterModel,
        ]) ?>
    <?php endif; ?>
    </thead>
    <tbody>
    <?= $view->renderFile(
        __DIR__ . '/body.php', [
        'attributes' => $attributes,
        'formatter' => $formatter,
        'collection' => $collection,
    ]) ?>
    </tbody>
    <?php if ($collection->count() && $showStatistic): ?>
        <tfoot>
        <tr>
            <td colspan="<?= count($attributes) ?>">
                <?= I18Next::t('web', 'collection.items_collection_count') ?>:
                <?= $collection->count() ?>

                <?php if (!empty($dataProvider)): ?>
                    <?= I18Next::t('web', 'collection.items_of') ?>
                    <?= $dataProvider->getTotalCount() ?>
                <?php endif; ?>
            </td>
        </tr>
        </tfoot>
    <?php endif; ?>
</table>

<?php
if (isset($dataProvider)) {
    echo PaginationWidget::widget(['dataProvider' => $dataProvider]);
}
?>

<?php //if (!$collection->isEmpty()): ?>
<!---->
<?php //else: ?>
<!--    <div class="alert alert-secondary" role="alert">-->
<!--        --><? //= I18Next::t('web', 'message.empty_list') ?>
<!--    </div>-->
<?php //endif; ?>
