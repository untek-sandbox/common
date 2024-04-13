<?php

namespace Untek\User\Authentication\Domain\Interfaces\Repositories;

use Untek\User\Authentication\Domain\Entities\TokenEntity;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Model\Repository\Interfaces\CrudRepositoryInterface;

interface TokenRepositoryInterface extends CrudRepositoryInterface
{

    /**
     * @param string $value
     * @param string $type
     * @return TokenEntity
     * @throws NotFoundException
     */
    public function findOneByValue(string $value, string $type): TokenEntity;
}
