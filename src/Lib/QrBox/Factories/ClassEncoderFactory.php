<?php

namespace Untek\Lib\QrBox\Factories;

use Untek\Component\Encoder\Encoders\GZipEncoder;
use Untek\Kaz\Egov\Qr\Encoders\ZipEncoder;
use Untek\Lib\QrBox\Encoders\Base64Encoder;
use Untek\Lib\QrBox\Encoders\HexEncoder;
use Untek\Lib\QrBox\Libs\ClassEncoder;

class ClassEncoderFactory
{

    public static function create(): ClassEncoder
    {
        $encoders = [
            'zip' => ZipEncoder::class,
            'gz' => new GZipEncoder(ZLIB_ENCODING_GZIP, 9),
            'gzDeflate' => new GZipEncoder(ZLIB_ENCODING_RAW, 9),
            'base64' => Base64Encoder::class,
            'b64' => Base64Encoder::class,
            'hex' => HexEncoder::class,
        ];
        $classEncoder = new ClassEncoder($encoders);
        return $classEncoder;
    }
}