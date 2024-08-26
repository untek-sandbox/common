<?php

namespace Untek\Component\Hydrator;

interface HydratorAwareInterface
{

    public function setHydrator(HydratorInterface $serializer): void;
}