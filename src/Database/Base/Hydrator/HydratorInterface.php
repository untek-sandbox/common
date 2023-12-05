<?php

namespace Untek\Database\Base\Hydrator;

use Untek\Model\Entity\Helpers\EntityHelper;

interface HydratorInterface
{

    public function dehydrate(object $entity): array;

    public function hydrate(array $item): object;
}