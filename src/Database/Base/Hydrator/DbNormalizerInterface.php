<?php

namespace Untek\Database\Base\Hydrator;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

interface DbNormalizerInterface //extends DenormalizerInterface, NormalizerInterface
{

    public function denormalize(mixed $data, string $type);

    public function normalize(mixed $object);
}