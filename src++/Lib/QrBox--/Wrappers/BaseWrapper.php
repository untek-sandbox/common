<?php

namespace Untek\Lib\QrBox\Wrappers;

abstract class BaseWrapper
{

    protected $encoders = [];

    public function getEncoders(): array
    {
        return $this->encoders;
    }

    public function setEncoders(array $encoders): void
    {
        $this->encoders = $encoders;
    }
}
