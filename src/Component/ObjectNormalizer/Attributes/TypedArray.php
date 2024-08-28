<?php

namespace Untek\Component\ObjectNormalizer\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class TypedArray
{

    public function __construct(public string $type)
    {
    }
}
