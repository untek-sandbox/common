<?php

use Untek\Framework\Socket\Domain\Repositories\Ram\ConnectionRepository;

return [
    'singletons' => [
        ConnectionRepository::class => ConnectionRepository::class,
    ],
];
