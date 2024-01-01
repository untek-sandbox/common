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

<?= \Untek\Component\Web\TwBootstrap\Widgets\Alert\AlertWidget::widget() ?>
<?= $content ?>

<?= ToastrWidget::widget(['view' => $view]) ?>
<?= $view->getCss()->render() ?>
<?= $view->getJs()->render() ?>

</body>
</html>
