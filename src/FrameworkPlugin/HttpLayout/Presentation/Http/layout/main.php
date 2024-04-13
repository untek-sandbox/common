<?php

/**
 * @var string $content
 * @var HtmlRenderInterface $view
 */

use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Component\Web\WebApp\Assets\AppAsset;

(new AppAsset())->register($view);

$view->getCss()->registerFile('/static/css/site.css');

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

<?= $content ?>

<?= $view->getCss()->render() ?>
<?= $view->getJs()->render() ?>

</body>
</html>
