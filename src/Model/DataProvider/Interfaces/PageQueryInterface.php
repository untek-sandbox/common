<?php

namespace Untek\Model\DataProvider\Interfaces;

use Untek\Model\DataProvider\Dto\PageRequest;

interface PageQueryInterface
{

    public function getPage(): PageRequest;

    public function setPage(PageRequest $pageRequest): void;
}