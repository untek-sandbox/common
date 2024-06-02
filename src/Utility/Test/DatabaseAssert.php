<?php

namespace Untek\Utility\Test;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert;
use Untek\Database\Eloquent\Infrastructure\Helpers\QueryBuilder\EloquentQueryBuilderHelper;

class DatabaseAssert extends Assert
{

    public function __construct(private Manager $manager)
    {
    }

    public function assertHasRowById(string $table, mixed $id): self
    {
        $this->assertHasRow($table, ['id' => $id]);
        return $this;
    }

    public function assertHasRow(string $table, array $condition): self
    {
        $first = $this->getFirst($table, $condition);
        $this->assertNotEmpty($first);
        return $this;
    }

    public function assertRowById(string $table, mixed $id, array $attributes): self
    {
        $this->assertRow($table, ['id' => $id], $attributes);
        return $this;
    }

    public function assertRow(string $table, array $condition, array $attributes): self
    {
        $first = $this->getFirst($table, $condition);
        $ff = [];
        foreach ($attributes as $name => $value) {
            $ff[$name] = $first[$name];
        }
        $this->assertEquals($ff, $attributes);
        return $this;
    }

    protected function getFirst(string $table, array $condition): array
    {
        $collection = $this->getAll($table, $condition);
        return (array) $collection->first();
    }

    protected function getAll(string $table, array $condition): Collection
    {
        $queryBuilder = $this->manager
            ->getConnection()
            ->table($table)
        ;
        EloquentQueryBuilderHelper::setWhere($condition, $queryBuilder);
        $collection = $queryBuilder->get();
        return $collection;
    }
}
