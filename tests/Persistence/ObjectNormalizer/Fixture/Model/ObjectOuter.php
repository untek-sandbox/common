<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model;

class ObjectOuter
{

    private ObjectInner $inner;
    private \DateTimeInterface $date;

    public function getInner(): ObjectInner
    {
        return $this->inner;
    }

    public function setInner(ObjectInner $inner): void
    {
        $this->inner = $inner;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }
}