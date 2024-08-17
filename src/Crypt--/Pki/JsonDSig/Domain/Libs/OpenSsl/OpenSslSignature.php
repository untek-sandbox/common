<?php

namespace Untek\Crypt\Pki\JsonDSig\Domain\Libs\OpenSsl;

use phpseclib\File\X509;
use Untek\Crypt\Base\Domain\Enums\OpenSslAlgoEnum;
use Untek\Crypt\Base\Domain\Exceptions\CertificateExpiredException;
use Untek\Crypt\Base\Domain\Exceptions\ExpiredException;
use Untek\Crypt\Base\Domain\Exceptions\FailCertificateSignatureException;
use Untek\Crypt\Base\Domain\Exceptions\FailSignatureException;
use Untek\Crypt\Base\Domain\Exceptions\InvalidDigestException;
use Untek\Crypt\Base\Domain\Helpers\EncodingHelper;
use Untek\Crypt\Pki\Domain\Helpers\RsaKeyHelper;
use Untek\Crypt\Pki\Domain\Libs\Rsa\RsaStoreInterface;
use Untek\Crypt\Pki\JsonDSig\Domain\Entities\SignatureEntity;
use Untek\Crypt\Pki\JsonDSig\Domain\Helpers\OpenSslHelper;
use Untek\Crypt\Pki\JsonDSig\Domain\Libs\C14n;
use Untek\Crypt\Pki\X509\Domain\Helpers\X509Helper;

class OpenSslSignature
{

    private $keyStore;
    private $ca;
    /*private $c14nProfile = 'default';
    private $c14nProfiles = [
        'default' => ['sort-string', 'json-unescaped-unicode', 'hex-string'],
        'lite' => ['json-unescaped-unicode'],
    ];*/

    public function __construct(RsaStoreInterface $keyStore)
    {
        $this->keyStore = $keyStore;
    }

    public function loadCA(string $ca) {
        $this->ca = $ca;
    }

    /*public function setC14nProfile(string $name) {
        $this->c14nProfile = $name;
    }*/

    public function sign($data, SignatureEntity $signatureEntity)
    {
        $digestBinaryValue = $this->getDigest($data, $signatureEntity);
        $signatureMethod = OpenSslAlgoEnum::nameToOpenSsl($signatureEntity->getSignatureMethod());
        $signatureBinaryValue = OpenSslHelper::sign($digestBinaryValue, $signatureMethod, $this->keyStore->getPrivateKey(), $this->keyStore->getPrivateKeyPassword());
        $digestValue = EncodingHelper::encode($digestBinaryValue, $signatureEntity->getDigestFormat());
        $signatureEntity->setDigestValue($digestValue);
        $signatureValue = EncodingHelper::encode($signatureBinaryValue, $signatureEntity->getSignatureFormat());
        $signatureEntity->setSignatureValue($signatureValue);
        $certificate = RsaKeyHelper::keyToLine($this->keyStore->getCertificate());
        $signatureEntity->setX509Certificate($certificate);
    }

    public function verify($data, SignatureEntity $signatureEntity)
    {
        $digestBinaryValue = $this->checkDigest($data, $signatureEntity);
        $signatureMethod = OpenSslAlgoEnum::nameToOpenSsl($signatureEntity->getSignatureMethod());
        $signatureBinaryValue = EncodingHelper::decode($signatureEntity->getSignatureValue(), $signatureEntity->getSignatureFormat());

        $x509 = new X509();
        $x509->loadCA($this->ca);
        $certArray = $x509->loadX509($signatureEntity->getX509Certificate());

        if (!$x509->validateSignature()) {
            throw new FailCertificateSignatureException('Fail certificate signature');
        }
        $now = new \DateTime('now', new \DateTimeZone(@date_default_timezone_get()));
        if (!$x509->validateDate($now)) {
            throw new CertificateExpiredException('Certificate expired');
        }

//        $publicKey = $certArray['tbsCertificate']['subjectPublicKeyInfo']['subjectPublicKey'];
        $publicKey = X509Helper::extractPublicKey($signatureEntity->getX509Certificate());
        $isVerify = OpenSslHelper::verify($digestBinaryValue, $signatureBinaryValue, $signatureMethod, $publicKey);
        if(!$isVerify) {
            throw new FailSignatureException('Fail signature');
        }
    }

    private function checkDigest($data, SignatureEntity $signatureEntity)
    {
        $digestBinaryValue = $this->getDigest($data, $signatureEntity);
//        $digestValue = EncodingHelper::encode($digestBinaryValue, $signatureEntity->getDigestFormat());
        $digestBinaryValue2 = EncodingHelper::decode($signatureEntity->getDigestValue(), $signatureEntity->getDigestFormat());
        if ($digestBinaryValue2 != $digestBinaryValue) {
            throw new InvalidDigestException('Fail digest');
        }
        return $digestBinaryValue;
    }

    private function getDigest($body, SignatureEntity $signatureEntity)
    {
        $c14nData = $this->getC14n($body, $signatureEntity->getC14nMethod());

        return hash($signatureEntity->getDigestMethod(), $c14nData, true);
    }

    private function getC14n($body, string $c14nMethod): string
    {
        //$profileConfig = $this->c14nProfiles[$this->c14nProfile];
        $profileConfig = explode(',', $c14nMethod);
        $c14n = new C14n($profileConfig);
        $c14nData = $c14n->encode($body);
        return $c14nData;
    }
}
