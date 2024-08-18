<?php

namespace Untek\Kaz\Egov\Qr\Encoders;

interface EncoderInterface extends \Untek\Core\Contract\Encoder\Interfaces\EncoderInterface
{

    public function encode($data);
    public function decode($encodedData);

}
