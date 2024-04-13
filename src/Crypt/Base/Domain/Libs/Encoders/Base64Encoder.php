<?php

namespace Untek\Crypt\Base\Domain\Libs\Encoders;

use Untek\Crypt\Base\Domain\Helpers\SafeBase64Helper;

class Base64Encoder implements EncoderInterface
{

    public function encode($data)
    {
        return SafeBase64Helper::encode($data);
    }

    public function decode($encodedData)
    {
        return SafeBase64Helper::decode($encodedData);
    }

}
