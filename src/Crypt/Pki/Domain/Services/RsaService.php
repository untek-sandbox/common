<?php

namespace Untek\Crypt\Pki\Domain\Services;

use Untek\Crypt\Base\Domain\Entities\CertificateInfoEntity;
use Untek\Crypt\Base\Domain\Enums\HashAlgoEnum;
use Untek\Crypt\Pki\Domain\Libs\Rsa\RsaStoreInterface;

class RsaService
{

    public function generatePair(RsaStoreInterface $store, $bits = 2048, string $algo = HashAlgoEnum::SHA256)
    {
        $rsa = new \phpseclib\Crypt\RSA();

        $rsa->setPrivateKeyFormat(\phpseclib\Crypt\RSA::PRIVATE_FORMAT_PKCS1);
        //$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_PKCS1);

        //define('CRYPT_RSA_EXPONENT', 65537);
        //define('CRYPT_RSA_SMALLEST_PRIME', 64); // makes it so multi-prime RSA is used
        $keys = $rsa->createKey($bits);

        dd($keys['publickey']);

        $store->setPublicKey($keys['publickey']);
        $store->setPrivateKey($keys['privatekey']);
    }
}
