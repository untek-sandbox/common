<?php

namespace Untek\Persistence\Contract\Interfaces;

interface RepositoryCountByInterface
{

    /**
     * Посчитать количестов записей, удовлетворяющих определенным критериям.
     *
     * @param array $criteria
     * @return int
     */
    public function countBy(array $criteria): int;
}