<?php

namespace Untek\Database\Base\Hydrator;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

interface DbNormalizerInterface //extends DenormalizerInterface, NormalizerInterface
{

    public function denormalize(array $data, string $type): object;

    public function normalize(object $object): array;
}