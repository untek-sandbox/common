<?php

/**
 * @var array $menu
 * @var HtmlRenderInterface $view
 * @var string $content
 */

use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Component\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;

?>

<div class="col-sm-6">
    <h1 class="m-0 text-dark">
        <?= $view->getAttribute('title', '') ?>
    </h1>
</div>

<div class="col-sm-6">
    <?php
    /** @var BreadcrumbWidget $breadcrumbWidget */
    $breadcrumbWidget = \Untek\Core\Container\Helpers\ContainerHelper::getContainer()->get(BreadcrumbWidget::class);
    $breadcrumbWidget->wrapTemplate = '<ol class="breadcrumb float-sm-right">{items}</ol>';
    if (count($breadcrumbWidget->items) > 1) {
        echo $breadcrumbWidget->render();
    }
    ?>
</div>
