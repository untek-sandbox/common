<?php

namespace Untek\Crypt\Base\Domain\Libs\Encoders;

use Untek\Crypt\Tunnel\Domain\Libs\Tunnel\JsonFormatter;
use Untek\Crypt\Tunnel\Domain\Libs\Tunnel\StringFormatter;
use Untek\Crypt\Tunnel\Domain\Libs\Tunnel\TokenFormatter;
use phpDocumentor\Reflection\Types\Object_;

class CryptoEncoder
{

    private $formatters = [
        StringFormatter::class,
        JsonFormatter::class,
        TokenFormatter::class,
    ];

    public function formatterInstanceByName(string $formatName, array $formatters = null)
    {
        $formatters = $formatters ?: $this->formatters;
        /** @var StringFormatter[] $formatters */
        $formatterClass = null;
        foreach ($formatters as $formatterClass) {
            if($formatterClass::name() == $formatName) {
                return $formatterClass;
            }
        }
    }

}
