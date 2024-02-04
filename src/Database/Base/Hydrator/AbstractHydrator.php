<?php

namespace Untek\Database\Base\Hydrator;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class AbstractHydrator implements HydratorInterface
{

    public function getNormalizer(): NormalizerInterface|DenormalizerInterface
    {
        return new DatabaseItemNormalizer();
    }
}