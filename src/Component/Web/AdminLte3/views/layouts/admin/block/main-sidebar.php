<?php

/**
 * @var array $menuConfigFile
 * @var HtmlRenderInterface $view
 * @var string $content
 * @var IdentityEntity $identity
 */

use App\User\Domain\Entities\IdentityEntity;
use Untek\Component\Web\Html\Helpers\Url;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Component\Web\AdminLte3\Widgets\Sidebar\SidebarWidget;

?>
<!-- Brand Logo -->
<a href="<?= Url::to(['/admin']) ?>" class="brand-link">
    <img src="/static/img/icon/cloud-network.png" alt="Logo" class="brand-image img-circle <!--elevation-3-->"
         style="opacity: .8">
    <span class="brand-text font-weight-light">Admin panel</span>
</a>

<!-- Sidebar -->
<div class="sidebar">

    <?php if (1==0 && !Yii::$app->user->isGuest): ?>
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $identity->getLogo() ?>" class="img-circle <!--elevation-2-->" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $identity->getUsername() ?></a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <!-- Example: nav nav-pills nav-sidebar flex-column nav-child-indent nav-flat nav-legacy -->
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
            data-accordion="false">
            <?= SidebarWidget::widget([
                    'menuConfigFile' => $menuConfigFile, // __DIR__ . '/../../../../../../config/menu/admin_sidebar.php',
            ]); ?>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->