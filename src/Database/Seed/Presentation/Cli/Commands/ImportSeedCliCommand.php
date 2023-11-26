<?php

namespace Untek\Database\Seed\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Database\Seed\Application\Commands\ImportSeedCommand;
use Untek\Database\Seed\Application\Queries\GetTablesQuery;
use Untek\Framework\Console\Symfony4\Question\ChoiceQuestion;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;

class ImportSeedCliCommand extends Command
{

    public function __construct(private CommandBusInterface $bus)
    {
        parent::__construct(null);
    }

    public static function getDefaultName(): string
    {
        return 'seed:import';
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

        $command = new ImportSeedCommand();
        $command->setTables($selectedTables);
        $this->bus->handle($command);

        return Command::SUCCESS;
    }
}
