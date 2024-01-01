<?php

namespace Untek\Crypt\Pki\JsonDSig\Domain\Libs\Encoders;

use Untek\Core\Contract\Encoder\Interfaces\EncoderInterface;

interface C14nEncoderInterface extends EncoderInterface
{

    public static function paramName(): string;

}
