<?php

namespace Untek\Component\Hydrator;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

interface RootNormalizerAwareInterface
{

    public function setRootNormalizer(NormalizerInterface|DenormalizerInterface $serializer): void;
}