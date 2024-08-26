<?php

namespace Untek\Component\Hydrator;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

interface HydratorInterface extends NormalizerInterface, DenormalizerInterface
{
}