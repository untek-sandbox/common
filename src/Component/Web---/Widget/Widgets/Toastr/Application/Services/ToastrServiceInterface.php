<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Application\Services;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Model\ToastrEntity;
use Untek\Core\Collection\Interfaces\Enumerable;

interface ToastrServiceInterface
{

    public function success($message, int $delay = null);

    public function info($message, int $delay = null);

    public function warning($message, int $delay = null);

    public function error($message, int $delay = null);

    public function add(string $type, $message, int $delay = null);

    /**
     * @return Enumerable | ToastrEntity[]
     */
    public function findAll(): Enumerable;
}
