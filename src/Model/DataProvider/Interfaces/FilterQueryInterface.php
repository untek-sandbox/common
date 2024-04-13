<?php

namespace Untek\Model\DataProvider\Interfaces;

interface FilterQueryInterface
{

    public function getFilter(): array;

    public function setFilter(array $filter): void;
}