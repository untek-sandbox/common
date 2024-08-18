<?php

namespace Untek\Lib\QrBox\Encoders;

class HexEncoder extends \Untek\Component\Encoder\Encoders\HexEncoder implements EntityEncoderInterface
{

    public function compressionRate(): float
    {
        return 2;
    }
}