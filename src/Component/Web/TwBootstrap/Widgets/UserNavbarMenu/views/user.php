<?php

/**
 * @var HtmlRenderInterface $view
 * @var UserInterface $identity
 * @var string $userMenuHtml
 * @var string $logoutUrl
 */

use Symfony\Component\Security\Core\User\UserInterface;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Core\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use Untek\Component\Web\Html\Helpers\Html;
use Untek\Component\I18Next\Facades\I18Next;

?>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
       aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user"></i>
        <?= $identity->getUserIdentifier() ?>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
        <?= $userMenuHtml ?>
        <a class="dropdown-item" href="#" onclick="$('#logout-form').submit()">
            <i class="fas fa-sign-out-alt"></i>
            <?= I18Next::t('authentication', 'auth.logout_title') ?>
        </a>
    </div>
</li>

<?= Html::beginForm([$logoutUrl], 'post', ['id' => 'logout-form']) . Html::endForm(); ?>
