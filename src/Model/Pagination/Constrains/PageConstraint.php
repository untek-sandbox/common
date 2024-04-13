<?php

namespace Untek\Model\Pagination\Constrains;

use Symfony\Component\Validator\Constraint;

class PageConstraint extends Constraint
{

    public int $max = 20;

    public function __construct($limit = 20, array $groups = null, $payload = null)
    {
        $options = [
            'max' => $limit,
        ];
        parent::__construct($options, $groups, $payload);
    }
}
