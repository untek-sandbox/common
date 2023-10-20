<?php

namespace Untek\Crypt\Base\Domain\Libs\Encoders;

class JsonEncoder implements EncoderInterface
{

    public function encode($data)
    {
        return json_encode($data, true);
    }

    public function decode($encodedData)
    {
        return json_decode($encodedData, true);
    }

}
