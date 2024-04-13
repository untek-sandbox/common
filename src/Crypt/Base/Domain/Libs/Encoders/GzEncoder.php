<?php

namespace Untek\Crypt\Base\Domain\Libs\Encoders;

class GzEncoder implements EncoderInterface
{

    private $level;
    private $encodingMode;

    public function __construct(int $level = 9, $encodingMode = FORCE_GZIP)
    {
        $this->level = $level;
        $this->encodingMode = $encodingMode;
    }

    public function encode($data)
    {
        return gzencode($data, $this->level, $this->encodingMode);
    }

    public function decode($encodedData)
    {
        return gzdecode($encodedData);
    }

}
