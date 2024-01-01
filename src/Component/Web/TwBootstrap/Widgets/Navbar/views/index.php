<?php

/**
 * @var HtmlRenderInterface $view
 * @var string $leftMenu
 * @var string $rightMenu
 */

use Untek\Bundle\Language\Symfony4\Widgets\Language\LanguageWidget;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Component\Web\AdminLte3\Widgets\NavbarMenu\NavbarMenuWidget;
use Untek\Component\Web\TwBootstrap\Widgets\UserNavbarMenu\UserNavbarMenuWidget;

$isFixedTop = false;

if ($isFixedTop) {
    $view->getCss()->registerCode('body {padding-top: 3.5rem;}');
}

$navbarStyle = 'dark';

?>

<nav class="navbar navbar-expand-lg navbar-<?= $navbarStyle ?> bg-<?= $navbarStyle ?> <?= $isFixedTop ? 'fixed-top' : '' ?>">
    <a class="navbar-brand" href="/">PDS v2</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01"
            aria-controls="navbarColor01" aria-expanded="false" aria-label="Переключатель навигации">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
            <?= $leftMenu ?>
        </ul>
        <ul class="navbar-nav d-flex flex-row">
            <?= $rightMenu ?>
        </ul>
    </div>
</nav>
