<?php

namespace Untek\Model\DataProvider\Interfaces;

interface QueryDataProviderInterface
{

    public function countByQuery(object $query): int;

    public function findByQuery(object $query): array;
}