<?php

namespace Untek\Utility\Test;

use Illuminate\Database\Capsule\Manager;

trait DatabaseAssertTrait
{

    abstract protected function get(string $id): object;

    protected function getDatabaseAssert(): DatabaseAssert
    {
        /** @var Manager $capsule */
        $capsule = $this->get(Manager::class);
        return new DatabaseAssert($capsule);
    }
}
