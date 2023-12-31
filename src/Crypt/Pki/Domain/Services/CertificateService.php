<?php

namespace Untek\Crypt\Pki\Domain\Services;

use Untek\Domain\Entity\Helpers\EntityHelper;
use Untek\Crypt\Base\Domain\Entities\CertificateInfoEntity;
use Untek\Crypt\Base\Domain\Enums\HashAlgoEnum;
use Untek\Crypt\Pki\Domain\Entities\CertificateSubjectEntity;
use Untek\Crypt\Pki\Domain\Entities\RsaKeyEntity;
use Untek\Crypt\Pki\Domain\Entities\SignatureEntity;
use Untek\Crypt\Pki\Domain\Helpers\RsaKeyHelper;
use Untek\Crypt\Pki\Domain\Libs\Rsa\Rsa;
use Untek\Crypt\Pki\Domain\Libs\Rsa\RsaStoreFile;
use Untek\Crypt\Pki\Domain\Libs\Rsa\RsaStoreRam;

class CertificateService
{

    public function make(RsaStoreFile $issuerStore, CertificateSubjectEntity $subjectEntity, string $algo = HashAlgoEnum::SHA256): RsaKeyEntity
    {
        $subjectArray = EntityHelper::toArray($subjectEntity);
        $subjectJson = RsaKeyHelper::subjectArrayToJson($subjectArray);
        //dd($subjectJson);
        $rsa = new Rsa($issuerStore);
        $signatureEntity = $rsa->sign($subjectJson, $algo);
        $arr = [
            'subject' => $subjectArray,
            'signature' => [
                'signature' => $signatureEntity->getSignatureBase64(),
                'format' => 'base64',
                'algorithm' => 'sha256',
            ],
        ];
        //dd([$subjectEntity->getPublicKey() == $issuerStore->getPublicKey()]);
        if ($subjectEntity->getPublicKey() == $issuerStore->getPublicKey()) {
            $arr['issuer'] = 'self';
        } else {
            $issuerCert = $issuerStore->getCertificate();
            //
            $issuerCertJson = RsaKeyHelper::pemToBin($issuerCert);
            $issuerCert = json_decode($issuerCertJson);
            //dd($issuerCert);
            $arr['issuer'] = $issuerCert;
            //dd($issuerCert);
        }
        //dd($arr);

        $json = json_encode($arr, JSON_PRETTY_PRINT);
        $keyEntity = new RsaKeyEntity(RsaKeyEntity::CERTIFICATE, $json);
        return $keyEntity;
    }

    public function verify(RsaKeyEntity $certEntity): bool
    {
        $cert = $certEntity->getRaw();
        $certArray = json_decode($cert, true);
        //dd($certArray);
        $subjectArray = $certArray['subject'];
        $subjectJson = RsaKeyHelper::subjectArrayToJson($subjectArray);
        if ($certArray['issuer'] == 'self' || $certArray['issuer'] == null) {
            $issuerPublicKey = $certArray['subject']['publicKey'];
        } else {
            $issuerPublicKey = $certArray['issuer']['subject']['publicKey'];
        }
        //dd($certArray['issuer']);
        $store = new RsaStoreRam();

        $store->setPublicKey($issuerPublicKey);
        $rsa = new Rsa($store);

        $signatureEntity = new SignatureEntity;
        $signatureEntity->setSignatureBase64($certArray['signature']['signature']);
        $signatureEntity->setAlgorithm($certArray['signature']['algorithm']);
        $isVerify = $rsa->verify($subjectJson, $signatureEntity);
        //dd($subjectJson);
        if ($isVerify) {
            $diff = intval($certArray['subject']['expireAt']) - time();
            if ($diff < 1) {
                return false;
            }
        }
        return $isVerify;
    }
}
