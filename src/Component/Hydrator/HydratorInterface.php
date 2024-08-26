<?php

namespace Untek\Component\Hydrator;

interface HydratorInterface
{

    public function hydrate(string $class, array $data): object;

    public function dehydrate(object $object): array;
}