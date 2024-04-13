<?php

namespace Untek\Model\Contract\Interfaces;

use Untek\Core\Contract\Common\Exceptions\NotFoundException;

interface RepositoryFindOneByIdInterface
{

    /**
     * @param int $id
     * @param array|null $relations
     * @return object
     * @throws NotFoundException
     */
    public function findOneById(int $id, ?array $relations = null): object;
}