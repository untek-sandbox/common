<?php

namespace Untek\Crypt\Base\Domain\Helpers;

class CryptKeyHelper
{

    public static function formatKeyBlock(string $key, int $width = 64): string
    {
        return wordwrap($key, $width, PHP_EOL, true);
    }

    public static function base64ToPem($key, $tag)
    {
        //$key = chunk_split($key);
//        $key = self::keyToLine($key);
        $key = self::formatKeyBlock($key, 64);
        $tag = mb_strtoupper($tag);
        $key = "-----BEGIN $tag-----\n$key\n-----END $tag-----";
        return $key;
    }
}
