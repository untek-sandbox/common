<?php

namespace Untek\Lib\QrBox\Encoders;

use Untek\Core\Contract\Encoder\Interfaces\EncoderInterface;

interface EntityEncoderInterface extends EncoderInterface
{

    public function compressionRate(): float;

}
