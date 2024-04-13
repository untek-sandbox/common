<?php

/**
 * @var HtmlRenderInterface $view
 * @var string $tableClass
 * @var array $headerRow
 * @var array $bodyRows
 */

use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

?>

<table class="<?= $tableClass ?>">
    <?php if ($headerRow): ?>
        <thead>
        <?= $view->renderFile(__DIR__ . '/rows.php', ['rows' => [$headerRow], 'type' => 'th']) ?>
        </thead>
    <?php endif; ?>
    <?php if ($bodyRows): ?>
        <tbody>
        <?= $view->renderFile(__DIR__ . '/rows.php', ['rows' => $bodyRows]) ?>
        </tbody>
    <?php endif; ?>
</table>
