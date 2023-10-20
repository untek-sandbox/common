<?php

namespace Untek\Crypt\Base\Domain\Libs\Encoders;

use phpseclib\Crypt\AES;
use phpseclib\Crypt\Base;

class AesEncoder implements EncoderInterface
{

    private $aes;

    public function __construct(string $key, int $mode = Base::MODE_CFB)
    {
        $this->aes = new AES(Base::MODE_CFB);
        $this->aes->setKey($key);
    }

    public function encode($data)
    {
        return $this->aes->encrypt($data);
    }

    public function decode($encodedData)
    {
        return $this->aes->decrypt($encodedData);
    }

}
