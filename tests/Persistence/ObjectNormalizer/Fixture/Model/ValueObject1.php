<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Model;

use Untek\Component\ValueObject\ValueObjectInterface;

class ValueObject1 implements ValueObjectInterface
{

    public function __construct(private string $value)
    {
    }

    public function get(): string
    {
        return $this->value;
    }
}