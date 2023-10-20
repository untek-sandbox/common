<?php

namespace Untek\Model\DataProvider\Dto;

class PageRequest
{

    private int $number = 1;
    private int $size = 10;

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }
}