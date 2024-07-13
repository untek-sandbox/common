<?php

namespace Untek\Database\Migration\Presentation\Cli\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Database\Base\Console\Traits\OverwriteDatabaseTrait;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;

class DownCommand extends BaseCommand
{

    use OverwriteDatabaseTrait;

    public static function getDefaultName(): string
    {
        return 'db:migrate:down';
    }

    public function getDescription(): string
    {
        return 'Migration down';
    }

    public function getHelp(): string
    {
        return 'This command down all migrations...';
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Migrate DOWN');

        if (!$this->isContinue($input, $output)) {
            return self::SUCCESS;
        }

        $historyCollection = $this->migrationService->allForDown();
        if (empty($historyCollection)) {
            $io->warning('No applied migrations found!');
            return self::SUCCESS;
        }

        $withConfirm = $input->getOption('withConfirm');
        if ($withConfirm) {
            $versionArray = ArrayHelper::getColumn($historyCollection, 'version');
            $versionArray = array_values($versionArray);
            $io->listing($versionArray);
        }

        if (!$this->isContinueQuestion('Down migrations?', $input, $io)) {
            return self::SUCCESS;
        }

        $this->runMigrate($historyCollection, 'down', $io);
        $io->success('Migrate DOWN success!');
        return self::SUCCESS;
    }

}
