<?php

namespace Untek\Utility\Test;

use Illuminate\Database\Capsule\Manager;
use PHPUnit\Framework\Assert;
use Untek\Database\Eloquent\Infrastructure\Helpers\QueryBuilder\EloquentQueryBuilderHelper;

class DatabaseAssert extends Assert
{

    public function __construct(private Manager $manager)
    {
    }

    public function assertHasRowById(string $table, mixed $id): void
    {
        $this->assertHasRow($table, ['id' => $id]);
    }

    public function assertHasRow(string $table, array $condition): void
    {
        $queryBuilder = $this->manager
            ->getConnection()
            ->table($table);
        EloquentQueryBuilderHelper::setWhere($condition, $queryBuilder);
        $collection = $queryBuilder->get();
        $this->assertNotEmpty($collection->first());
    }
}
