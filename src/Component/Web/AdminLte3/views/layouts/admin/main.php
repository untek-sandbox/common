<?php

/**
 * @var array $menuConfigFile
 * @var HtmlRenderInterface $view
 * @var string $content
 */

use Untek\Component\Web\AdminApp\Assets\AdminAppAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\ToastrWidget;

(new AdminAppAsset())->register($view);

//$this->registerCssFile('/static/css/footer.css');
//$this->registerCssFile('/static/css/site.css');

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?? '' ?></title>
    <?= $view->getCss()->render() ?>
</head>
<body>

<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-dark">
        <?= $view->renderFile(__DIR__ . '/block/main-header.php') ?>
    </nav>

    <?php if(!empty($menuConfigFile)): ?>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <?= $view->renderFile(
                __DIR__ . '/block/main-sidebar.php', [
                'menuConfigFile' => $menuConfigFile,
            ]) ?>
        </aside>
    <?php endif; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <?= $view->renderFile(__DIR__ . '/block/content-header.php') ?>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <?= \Untek\Component\Web\TwBootstrap\Widgets\Alert\AlertWidget::widget() ?>
                <?= $content ?>
            </div>
        </div>
    </div>

    <aside class="control-sidebar control-sidebar-dark">
        <?= $view->renderFile(__DIR__ . '/block/control-sidebar.php') ?>
    </aside>

    <footer class="main-footer">
        <?= $view->renderFile(__DIR__ . '/block/main-footer.php') ?>
    </footer>
</div>

<a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
    <i class="fas fa-chevron-up"></i>
</a>

<?= ToastrWidget::widget(['view' => $view]) ?>
<?= $view->getCss()->render() ?>
<?= $view->getJs()->render() ?>

</body>
</html>
