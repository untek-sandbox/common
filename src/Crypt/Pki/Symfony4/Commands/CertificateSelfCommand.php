<?php

namespace Untek\Crypt\Pki\Symfony4\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\FileSystem\Helpers\FilePathHelper;
use Untek\Lib\Components\Time\Enums\TimeEnum;
use Untek\Crypt\Base\Domain\Entities\CertificateInfoEntity;
use Untek\Crypt\Base\Domain\Enums\HashAlgoEnum;
use Untek\Crypt\Pki\Domain\Entities\CertificateSubjectEntity;
use Untek\Crypt\Pki\Domain\Libs\Rsa\RsaStoreFile;
use Untek\Crypt\Pki\Domain\Services\CertificateService;

class CertificateSelfCommand extends BaseGeneratorCommand
{

    protected static $defaultName = 'crypt:certificate:self';
    private $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        parent::__construct(self::$defaultName);
        $this->certificateService = $certificateService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $issuerStore = new RsaStoreFile(FilePathHelper::path(getenv('RSA_CA_DIRECTORY')));
        //$subjectStore = new RsaStoreFile(FileHelper::path(getenv('RSA_HOST_DIRECTORY')));

        $subjectEntity = new CertificateSubjectEntity;
        $subjectEntity->setType('company');
        $subjectEntity->setName('Root');
        $subjectEntity->setTrustLevel(300);
        $subjectEntity->setExpire(TimeEnum::SECOND_PER_YEAR * 10);
        $subjectEntity->setPublicKey($issuerStore->getPublicKey());

        $cert = $this->certificateService->make($issuerStore, $subjectEntity, HashAlgoEnum::SHA256);

        $isVerify = $this->certificateService->verify($cert);
        if ($isVerify) {
            $issuerStore->setCertificate($cert);
            $output->writeln('<fg=green>Success certification!</>');
        } else {
            $output->writeln('<fg=red>Error certification!</>');
        }

        return 0;
    }

}
