<?php

namespace Untek\Component\Hydrator;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

interface HydratorAwareInterface
{

    public function setHydrator(NormalizerInterface|DenormalizerInterface $serializer): void;
}