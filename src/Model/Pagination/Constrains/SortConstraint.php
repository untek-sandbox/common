<?php

namespace Untek\Model\Pagination\Constrains;

use Symfony\Component\Validator\Constraint;

class SortConstraint extends Constraint
{

    public array $fields = [];

    public function __construct($fields = null, array $groups = null, $payload = null)
    {
        $options = [
            'fields' => $fields,
        ];
        parent::__construct($options, $groups, $payload);
    }
}
