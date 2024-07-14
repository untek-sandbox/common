<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Application\Services;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Model\ToastrEntity;
use Untek\Model\Validator\Exceptions\UnprocessibleEntityException;
use Untek\Core\Collection\Interfaces\Enumerable;

interface ToastrRepositoryInterface
{

    /**
     * @param ToastrEntity $toastrEntity
     * @return mixed
     * @throws UnprocessibleEntityException
     */
    public function create(ToastrEntity $toastrEntity);

    /**
     * @return \Untek\Core\Collection\Interfaces\Enumerable | ToastrEntity[]
     */
    public function findAll(): Enumerable;
}
