<?php

namespace Untek\Kaz\Egov\Qr\Wrappers;

use Untek\Kaz\Egov\Qr\Entities\BarCodeEntity;

interface WrapperInterface
{

    public function getEncoders(): array;

    public function setEncoders(array $encoders): void;

    public function isMatch(string $encodedData): bool;

    public function encode(BarCodeEntity $entity): string;

    public function decode(string $encodedData): BarCodeEntity;
}
