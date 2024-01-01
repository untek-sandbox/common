<?php

/**
 * @var HtmlRenderInterface $view
 * @var string $loginUrl
 */

use Untek\Component\I18Next\Facades\I18Next;
use Untek\Component\Web\Html\Helpers\Url;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

?>

<li class="nav-item">
    <a class="nav-link" href="<?= Url::to($loginUrl) ?>">
        <i class="fas fa-sign-in-alt"></i>
        <?= I18Next::t('authentication', 'auth.title') ?>
    </a>
</li>
