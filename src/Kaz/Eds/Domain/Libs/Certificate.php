<?php

namespace Untek\Kaz\Eds\Domain\Libs;

use phpseclib\File\X509;
use Untek\Crypt\Base\Domain\Exceptions\CertificateExpiredException;
use Untek\Crypt\Base\Domain\Exceptions\FailCertificateSignatureException;

class Certificate
{

    private $ca;
    private $verifySignature = true;
    private $verifyDate = true;

    public function setCa(string $ca): void
    {
        $this->ca = $ca;
    }

    public function setVerifySignature(bool $verifySignature): void
    {
        $this->verifySignature = $verifySignature;
    }

    public function setVerifyDate(bool $verifyDate): void
    {
        $this->verifyDate = $verifyDate;
    }

    public function verify(string $certificate): void
    {
        $x509 = new X509();
        $x509->loadCA($this->ca);
        $certArray = $x509->loadX509($certificate);
        if ($this->verifySignature && !$x509->validateSignature()) {
            throw new FailCertificateSignatureException();
        }
        $now = new \DateTime('now', new \DateTimeZone(@date_default_timezone_get()));
        if ($this->verifyDate && !$x509->validateDate($now)) {
            throw new CertificateExpiredException();
        }
    }
}
