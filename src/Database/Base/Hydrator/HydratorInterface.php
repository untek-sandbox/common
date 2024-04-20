<?php

namespace Untek\Database\Base\Hydrator;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Model\Entity\Helpers\EntityHelper;

DeprecateHelper::hardThrow();

interface HydratorInterface
{

    public function dehydrate(object $entity): array;

    public function hydrate(array $item, object $entity = null): object;
}