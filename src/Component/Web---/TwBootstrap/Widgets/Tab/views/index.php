<?php

/**
 * @var HtmlRenderInterface $view
 * @var array $items
 * @var string $class
 */

use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

?>

<ul class="nav nav-tabs <?= $class ?>">
    <?php foreach ($items as $item):
        $title = $item['title'];
        $isActive = $item['is_active'] ?? false;
        $itemClass = $isActive ? 'active' : '';
    ?>
        <li class="nav-item">
            <a class="nav-link <?= $itemClass ?>" href="<?= $item['url'] ?>" role="tab">
                <?= $title ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
