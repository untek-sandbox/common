<?php

namespace Untek\Crypt\Base\Domain\Enums;

/**
 * Class HashAlgoEnum
 *
 * @package Untek\Crypt\Base\Domain\Enums
 */
class HashAlgoEnum
{

    const MD2 = 'md2';

    const MD4 = 'md4';

    const MD5 = 'md5';

    const SHA1 = 'sha1';

    const SHA224 = 'sha224';

    const SHA256 = 'sha256';

    const SHA384 = 'sha384';

    const SHA512 = 'sha512';

    const RIPEMD128 = 'ripemd128';

    const RIPEMD160 = 'ripemd160';

    const RIPEMD256 = 'ripemd256';

    const RIPEMD320 = 'ripemd320';

    const WHIRLPOOL = 'whirlpool';

    const TIGER128_3 = 'tiger128,3';

    const TIGER160_3 = 'tiger160,3';

    const TIGER192_3 = 'tiger192,3';

    const TIGER128_4 = 'tiger128,4';

    const TIGER160_4 = 'tiger160,4';

    const TIGER192_4 = 'tiger192,4';

    const SNEFRU = 'snefru';

    const SNEFRU256 = 'snefru256';

    const GOST = 'gost';

    const GOST_CRYPTO = 'gost-crypto';

    const ADLER32 = 'adler32';

    const CRC32 = 'crc32';

    const CRC32B = 'crc32b';

    const FNV132 = 'fnv132';

    const FNV1A32 = 'fnv1a32';

    const FNV164 = 'fnv164';

    const FNV1A64 = 'fnv1a64';

    const JOAAT = 'joaat';

    const HAVAL128_3 = 'haval128,3';

    const HAVAL160_3 = 'haval160,3';

    const HAVAL192_3 = 'haval192,3';

    const HAVAL224_3 = 'haval224,3';

    const HAVAL256_3 = 'haval256,3';

    const HAVAL128_4 = 'haval128,4';

    const HAVAL160_4 = 'haval160,4';

    const HAVAL192_4 = 'haval192,4';

    const HAVAL224_4 = 'haval224,4';

    const HAVAL256_4 = 'haval256,4';

    const HAVAL128_5 = 'haval128,5';

    const HAVAL160_5 = 'haval160,5';

    const HAVAL192_5 = 'haval192,5';

    const HAVAL224_5 = 'haval224,5';

    const HAVAL256_5 = 'haval256,5';

    const DSS1 = 'dss1';

    const RMD160 = 'rmd160';

    public static $openSsl = [
        self::SHA1 => OPENSSL_ALGO_SHA1,
        self::MD5 => OPENSSL_ALGO_MD5,
        self::MD4 => OPENSSL_ALGO_MD4,
        //self::MD2 => OPENSSL_ALGO_MD2,
        //self::DSS1 => OPENSSL_ALGO_DSS1,
        self::SHA224 => OPENSSL_ALGO_SHA224,
        self::SHA256 => OPENSSL_ALGO_SHA256,
        self::SHA384 => OPENSSL_ALGO_SHA384,
        self::SHA512 => OPENSSL_ALGO_SHA512,
        self::RMD160 => OPENSSL_ALGO_RMD160,
    ];

    public static function nameToOpenSsl(string $algo) {
        return self::$openSsl[$algo];
    }

    public static function openSslToName(string $algo) {
        $assoc = array_flip(self::$openSsl);
        return $assoc[$algo];
    }
}


/*
$algos = [];
foreach (hash_algos() as $algo) {
    $algos[] = [
        'name' => $algo,
        'value' => $algo,
    ];
}
EnumGeneratorHelper::generate([
    'className' => '@ZnSandbox/Bundle/Crypt/Enums/HashAlgoEnum',
    'const' => $algos,
]);
 */