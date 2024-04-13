<?php

namespace Untek\Crypt\Base\Domain\Helpers;

use Untek\Crypt\Base\Domain\Enums\EncodingEnum;
use Untek\Crypt\Base\Domain\Exceptions\UnknownEncodingException;

class EncodingHelper
{

    public static function encode($binaryValue, string $encoding)
    {
        switch ($encoding) {
            case EncodingEnum::BASE64:
                return base64_encode($binaryValue);
                break;
            case EncodingEnum::HEX:
                return bin2hex($binaryValue);
                break;
            case EncodingEnum::BINARY:
                return $binaryValue;
            default:
                throw new UnknownEncodingException();
        }
    }

    public static function decode($encoded, string $encoding)
    {
        switch ($encoding) {
            case EncodingEnum::BASE64:
                return base64_decode($encoded);
                break;
            case EncodingEnum::HEX:
                return hex2bin($encoded);
                break;
            case EncodingEnum::BINARY:
                return $encoded;
            default:
                throw new UnknownEncodingException();
        }
    }
}
