<?php

return [
    'singletons' => [
        'Untek\Component\Web\Menu\Domain\Interfaces\Services\MenuServiceInterface' => 'Untek\Component\Web\Menu\Domain\Services\MenuService',
        'Untek\Component\Web\Menu\Domain\Interfaces\Repositories\MenuRepositoryInterface' => 'Untek\Component\Web\Menu\Domain\Repositories\File\MenuRepository',
    ],
];
