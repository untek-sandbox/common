<?php

namespace Untek\Crypt\Base\Domain\Helpers;

class SafeBase64Helper
{

    public static function decode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function encode($input): string
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

}
