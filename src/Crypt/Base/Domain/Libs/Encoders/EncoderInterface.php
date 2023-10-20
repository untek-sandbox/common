<?php

namespace Untek\Crypt\Base\Domain\Libs\Encoders;

interface EncoderInterface
{

    public function encode($data);
    public function decode($encodedData);

}