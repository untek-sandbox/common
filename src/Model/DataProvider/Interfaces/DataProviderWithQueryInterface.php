<?php

namespace Untek\Model\DataProvider\Interfaces;

interface DataProviderWithQueryInterface
{

    public function countByQuery(object $query): int;

    public function findByQuery(object $query): array;
}