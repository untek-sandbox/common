<?php

namespace Untek\Crypt\Base\Domain\Enums;

class OpenSslAlgoEnum
{

    const SHA1 = 'openssl-sha1';
    const MD5 = 'openssl-md5';
    const MD4 = 'openssl-md4';
    const MD2 = 'openssl-md2';
    const DSS1 = 'openssl-dss1';
    const SHA224 = 'openssl-sha224';
    const SHA256 = 'openssl-sha256';
    const SHA384 = 'openssl-sha384';
    const SHA512 = 'openssl-sha512';
    const RMD160 = 'openssl-rmd160';

    public static $openSsl = [

    ];

    public static function assoc(): array {
        return [
            self::SHA1 => OPENSSL_ALGO_SHA1,
            self::MD5 => OPENSSL_ALGO_MD5,
            self::MD4 => OPENSSL_ALGO_MD4,
//        self::MD2 => OPENSSL_ALGO_MD2,
//        self::DSS1 => OPENSSL_ALGO_DSS1,
            self::SHA224 => OPENSSL_ALGO_SHA224,
            self::SHA256 => OPENSSL_ALGO_SHA256,
            self::SHA384 => OPENSSL_ALGO_SHA384,
            self::SHA512 => OPENSSL_ALGO_SHA512,
            self::RMD160 => OPENSSL_ALGO_RMD160,
        ];
    }

    public static function nameToOpenSsl(string $algo): int {
        $assoc = self::assoc();
        return $assoc[$algo];
    }

    /*public static function openSslToName(string $algo): string {
        $assoc = array_flip(self::$openSsl);
        return $assoc[$algo];
    }*/
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