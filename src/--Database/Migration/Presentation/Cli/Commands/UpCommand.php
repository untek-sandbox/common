<?php

namespace Untek\Database\Migration\Presentation\Cli\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;

class UpCommand extends BaseCommand
{

    public static function getDefaultName(): string
    {
        return 'db:migrate:up';
    }

    public function getDescription(): string
    {
        return 'Migration up';
    }

    public function getHelp(): string
    {
        return 'This command up all migrations...';
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Migrate UP');

        $filteredCollection = $this->migrationService->allForUp();
        if (empty($filteredCollection)) {
            $io->warning('Migrations up to date!');
            return self::SUCCESS;
        }

        $withConfirm = $input->getOption('withConfirm');
        if ($withConfirm) {
            $versionArray = ArrayHelper::getColumn($filteredCollection, 'version');
            $versionArray = array_values($versionArray);
            $io->listing($versionArray);
        }

        if (!$this->isContinueQuestion('Apply migrations?', $input, $io)) {
            return self::SUCCESS;
        }

        $this->runMigrate($filteredCollection, 'up', $io);
        $io->success('Migrate UP success!');
        return self::SUCCESS;
    }

}
