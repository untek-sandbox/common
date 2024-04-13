<?php

/**
 * @var array $menuConfigFile
 * @var HtmlRenderInterface $view
 * @var string $content
 */

use Untek\Component\Web\AdminApp\Assets\AdminAppAsset;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

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
    <script data-main="/rjs/index?ver=<?= hash_file(
        'crc32b',
        __DIR__ . '/../../../../../../../../../public/rjs/index.js'
    ) ?>"
            src="/rjs/vendor/requirejs/require.js"></script>
</head>
<body>

<?= $content ?>

<?= '' //ToastrWidget::widget(['view' => $this])  ?>
<?= $view->getCss()->render() ?>
<?= '' /*$view->getJs()->render()*/ ?>

</body>
</html>
