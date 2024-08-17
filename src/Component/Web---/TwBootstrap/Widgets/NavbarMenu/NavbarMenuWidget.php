<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\NavbarMenu;

use Untek\Component\Web\TwBootstrap\Widgets\Menu\MenuWidget;

class NavbarMenuWidget extends MenuWidget
{

    public $itemOptions = [
        'class' => 'nav-item',
        'tag' => 'span',
    ];
    public $linkTemplate =
        '<a href="{url}" class="nav-link {class}">
            {icon}
                {label}
                {treeViewIcon}
                {badge}
        </a>';
    public $submenuTemplate = '<ul class="nav nav-treeview">{items}</ul>';
    public $activateParents = true;
    public $treeViewIcon = '<i class="right fas fa-angle-left"></i>';

    public function __construct(array $items)
    {
        $this->items = $items;
    }
}