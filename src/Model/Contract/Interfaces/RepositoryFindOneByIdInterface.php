<?php

namespace Untek\Model\Contract\Interfaces;

use Untek\Core\Contract\Common\Exceptions\NotFoundException;

interface RepositoryFindOneByIdInterface
{

    /**
     * @param int $id
     * @return object
     * @throws NotFoundException
     */
    public function findOneById(int $id): object;
}