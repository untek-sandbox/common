<?php

use Untek\Lib\Web\View\Libs\View;
use Untek\Lib\Web\View\Resources\Css;
use Untek\Lib\Web\View\Resources\Js;

return [
    'singletons' => [
        View::class => View::class,
        Css::class => Css::class,
        Js::class => Js::class,
    ],
];
