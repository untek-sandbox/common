<?php

namespace Untek\Model\Contract\Interfaces;

interface RepositoryCountByInterface
{

    /**
     * @param array $criteria
     * @return int
     */
    public function countBy(array $criteria): int;
}