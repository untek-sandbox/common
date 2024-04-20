<?php

namespace Untek\Model\DataProvider\Interfaces;

interface SortQueryInterface
{

    public function getSort(): array;

    public function setSort(array $sort): void;
}