<?php

namespace Untek\Crypt\Pki\Symfony4\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Untek\Core\FileSystem\Helpers\FilePathHelper;
use Untek\Crypt\Base\Domain\Entities\CertificateInfoEntity;
use Untek\Crypt\Pki\Domain\Libs\Rsa\RsaStoreFile;
use Untek\Crypt\Pki\Domain\Services\RsaService;

class RsaGenerateCommand extends BaseGeneratorCommand
{

    protected static $defaultName = 'crypt:rsa:generate';
    private $rsaService;

    public function __construct(RsaService $rsaService)
    {
        parent::__construct(self::$defaultName);
        $this->rsaService = $rsaService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rsaDir = FilePathHelper::path(getenv('RSA_DIRECTORY'));

        do {
            $question = new Question('Enter project name: ');
            $helper = $this->getHelper('question');
            $profileName = $helper->ask($input, $output, $question);
            $dir = $rsaDir . DIRECTORY_SEPARATOR . $profileName;
            $isValid = true;
            if (empty($profileName)) {
                $output->writeln('<fg=yellow>Empty!</>');
                $isValid = false;
            }
            if (is_dir($dir)) {
                $output->writeln('<fg=yellow>Already exists!</>');
                $isValid = false;
            }
        } while (!$isValid);

        $subjectStore = new RsaStoreFile($rsaDir . DIRECTORY_SEPARATOR . $profileName);
        $subjectStore->enableWrite();

        $this->rsaService->generatePair($subjectStore);
        $output->writeln('<fg=green>Success generated!</>');

        return 0;
    }

}
