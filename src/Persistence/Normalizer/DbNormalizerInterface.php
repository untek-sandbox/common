<?php

namespace Untek\Persistence\Normalizer;

interface DbNormalizerInterface
{

    public function denormalize(array $data, string $type): object;

    public function normalize(object $object): array;
}