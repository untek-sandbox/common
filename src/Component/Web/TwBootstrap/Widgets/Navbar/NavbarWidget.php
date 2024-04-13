<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Navbar;

use Untek\Component\Web\Widget\Base\BaseWidget2;

class NavbarWidget extends BaseWidget2
{

    public $leftMenu;
    public $rightMenu;

    public function run(): string
    {
        return $this->render('index', [
            'leftMenu' => $this->leftMenu,
            'rightMenu' => $this->rightMenu,
        ]);
    }
}