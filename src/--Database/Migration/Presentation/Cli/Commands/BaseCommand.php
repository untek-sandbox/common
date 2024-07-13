<?php

namespace Untek\Database\Migration\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Untek\Database\Migration\Domain\Model\Migration;
use Untek\Database\Migration\Application\Services\MigrationService;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Framework\Console\Symfony4\Widgets\LogWidgetIo;

abstract class BaseCommand extends Command
{

    public function __construct(protected MigrationService $migrationService)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addOption(
                'withConfirm',
                null,
                InputOption::VALUE_REQUIRED,
                'Your selection migrations',
                true
            );
    }

    protected function isContinueQuestion(string $question, InputInterface $input, SymfonyStyle $io): bool
    {
        $withConfirm = $input->getOption('withConfirm');
        if (!$withConfirm) {
            return true;
        }
        return $io->confirm($question);
    }

    protected function runMigrate($collection, string $method, SymfonyStyle $io)
    {
        $logWidget = new LogWidgetIo($io);
        $logWidget->setPretty(true);
        $logWidget->setLineLength(64);
        /** @var Migration[] $collection */
        foreach ($collection as $migrationEntity) {
            $logWidget->start($migrationEntity->version);
            if ($method == 'up') {
                $this->migrationService->upMigration($migrationEntity);
            } else {
                $this->migrationService->downMigration($migrationEntity);
            }
            $logWidget->finishSuccess();
        }
        $io->newLine();
    }
}
