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
        $first = $this->findFirst($table, $condition);
        $this->assertNotEmpty($first);
        return $this;
    }

    public function assertAll(string $table, array $condition, array $expectedCollection): self
    {
        $collectionFromDb = $this->findAll($table, $condition);
        $this->assertEquals(count($expectedCollection), count($collectionFromDb));
        $new = [];
        foreach ($expectedCollection as $index => $expectedAttributes) {
            $rowFromDb = (array)$collectionFromDb[$index];
            $actualAttributes = $this->extractAttributes($expectedAttributes, $rowFromDb);
            $new[] = $actualAttributes;
        }
        $this->assertEquals($expectedCollection, $new);
        return $this;
    }

    public function findRowById(string $table, mixed $id): array
    {
        $condition = ['id' => $id];
        return $this->findFirst($table, $condition);
    }

    public function findRow(string $table, array $condition): array
    {
        return $this->findFirst($table, $condition);
    }

    public function assertRowById(string $table, mixed $id, array $expectedAttributes): self
    {
        $this->assertRow($table, ['id' => $id], $expectedAttributes);
        return $this;
    }

    public function updateRowById(string $table, mixed $id, array $values): self
    {
        $condition = ['id' => $id];
        $queryBuilder = $this->manager
            ->getConnection()
            ->table($table)
        ;
        EloquentQueryBuilderHelper::setWhere($condition, $queryBuilder);
        $queryBuilder->update($values);
        return $this;
    }

    public function deleteRowById(string $table, mixed $id): self
    {
        $condition = ['id' => $id];
        $queryBuilder = $this->manager
            ->getConnection()
            ->table($table)
        ;
        EloquentQueryBuilderHelper::setWhere($condition, $queryBuilder);
        $queryBuilder->delete();
        return $this;
    }

    public function assertRow(string $table, array $condition, array $expectedAttributes): self
    {
        $rowFromDb = $this->findFirst($table, $condition);
        $this->assertNotEmpty($rowFromDb, 'Record not found.');
        $actualAttributes = $this->extractAttributes($expectedAttributes, $rowFromDb);
        $this->assertEquals($expectedAttributes, $actualAttributes);
        return $this;
    }
    
    public function truncateTable(string $table): self
    {
        $queryBuilder = $this->manager
            ->getConnection()
            ->table($table)
            ->truncate()
        ;
        return $this;
    }

    protected function extractAttributes(array $expectedAttributes, array $rowFromDb): array {
        $actualAttributes = [];
        foreach ($expectedAttributes as $name => $value) {
            $actualAttributes[$name] = $rowFromDb[$name];
        }
        return $actualAttributes;
    }

    protected function findFirst(string $table, array $condition): array
    {
        $collection = $this->findAll($table, $condition);
        return (array) $collection->first();
    }

    protected function findAll(string $table, array $condition): Collection
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
