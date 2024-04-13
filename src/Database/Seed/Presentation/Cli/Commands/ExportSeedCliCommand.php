<?php

namespace Untek\Database\Seed\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Database\Seed\Application\Commands\ExportSeedCommand;
use Untek\Database\Seed\Application\Queries\GetTablesQuery;
use Untek\Framework\Console\Symfony4\Question\ChoiceQuestion;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Framework\Console\Symfony4\Widgets\LogWidgetIo;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;

class ExportSeedCliCommand extends Command
{

    public function __construct(private CommandBusInterface $bus)
    {
        parent::__construct(null);
    }

    public static function getDefaultName(): string
    {
        return 'seed:export';
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $query = new GetTablesQuery();
        $tables = $this->bus->handle($query);

        $question = new ChoiceQuestion(
            'Select tables for import',
            $tables,
            'a'
        );
        $question->setMultiselect(true);
        $selectedTables = $io->askQuestion($question);

        $logWidget = new LogWidgetIo($io);
        $logWidget->setPretty(true);
        $logWidget->setLineLength(64);

        $cb = function (string $tableName) use ($logWidget) {
            $logWidget->start($tableName);
            $logWidget->finishSuccess();
        };

        $command = new ExportSeedCommand();
        $command->setTables($selectedTables);
        $command->setProgressCallback($cb);
        $this->bus->handle($command);

        $io->success('Export completed successfully.');

        return Command::SUCCESS;
    }
}
