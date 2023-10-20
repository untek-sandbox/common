<?php

namespace Untek\Model\Pagination\Constrains;

use Symfony\Component\Validator\Constraint;

class ExpandConstraint extends Constraint
{

    public array $relations = [];

    public function __construct($relations = null, array $groups = null, $payload = null)
    {
        $options = [
            'relations' => $relations,
        ];
        parent::__construct($options, $groups, $payload);
    }
}
