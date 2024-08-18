<?php

namespace Untek\Kaz\Eds\Domain\Services;

use phpseclib\File\X509;
use Untek\Domain\Service\Base\BaseCrudService;
use Untek\Domain\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Crypt\Base\Domain\Exceptions\CertificateExpiredException;
use Untek\Crypt\Base\Domain\Exceptions\FailCertificateSignatureException;
use Untek\Kaz\Eds\Domain\Entities\CertificateEntity;
use Untek\Kaz\Eds\Domain\Interfaces\Repositories\CertificateRepositoryInterface;
use Untek\Kaz\Eds\Domain\Interfaces\Services\CertificateServiceInterface;

/**
 * @method CertificateRepositoryInterface getRepository()
 */
class CertificateService /*extends BaseCrudService*/ implements CertificateServiceInterface
{

    /*public function __construct(EntityManagerInterface $em)
    {
        $this->setEntityManager($em);
    }*/

    public function getEntityClass(): string
    {
        return CertificateEntity::class;
    }

    public function verifyByCa(string $certificate, string $ca): void
    {
        $x509 = new X509();
        $x509->loadCA($ca);
        $certArray = $x509->loadX509($certificate);
        if (!$x509->validateSignature()) {
            throw new FailCertificateSignatureException();
        }
        $now = new \DateTime('now', new \DateTimeZone(@date_default_timezone_get()));
        if (!$x509->validateDate($now)) {
            throw new CertificateExpiredException();
        }
    }
}
