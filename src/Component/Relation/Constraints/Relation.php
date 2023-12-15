<?php

namespace Untek\Component\Relation\Constraints;

use Symfony\Component\Validator\Constraint;

class Relation extends Constraint
{

    public $foreignEntityClass;
    public $message = 'Item with ID "{{ value }}" not found';
}
