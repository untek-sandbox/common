<?php

namespace Untek\Model\DataProvider\Interfaces;

interface ExpandQueryInterface
{

    public function getExpand(): array;

    public function setExpand(array $expand): void;
}