<?php

namespace Untek\Kaz\Egov\Qr\Encoders;

interface EntityEncoderInterface extends EncoderInterface
{

    public function compressionRate(): float;

}
