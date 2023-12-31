<?php

namespace Untek\Database\Fixture\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Database\Fixture\Domain\Entities\FixtureEntity;
use Untek\Framework\Console\Symfony4\Question\ChoiceQuestion;
use Untek\Framework\Console\Symfony4\Widgets\LogWidget;

class ExportCommand extends BaseCommand
{
    protected static $defaultName = 'db:fixture:export';

    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Export fixture data to files')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white># Fixture EXPORT</>');

        /** @var FixtureEntity[]|Enumerable $tableCollection */
        $tableCollection = $this->fixtureService->allFixtures();
        //dd($tableCollection->toArray());

        if ($tableCollection->count() == 0) {
            $output->writeln('');
            $output->writeln('<fg=magenta>No tables in database!</>');
            $output->writeln('');
            return 0;
        }

        $withConfirm = $input->getOption('withConfirm');

        $tableNameArray = CollectionHelper::getColumn($tableCollection, 'name');
        if ($withConfirm) {
            $output->writeln('');
            $question = new ChoiceQuestion(
                'Select tables for export',
                $tableNameArray,
                'a'
            );
            $question->setMultiselect(true);
            $selectedTables = $this->getHelper('question')->ask($input, $output, $question);
        } else {
            $selectedTables = $tableNameArray;
        }

        $output->writeln('');

        $logWidget = new LogWidget($output);
        $logWidget->setPretty(true);
        $logWidget->setLineLength(40);
        foreach ($selectedTables as $tableName) {
            $logWidget->start(' ' . $tableName);
            $this->fixtureService->exportTable($tableName);
            $logWidget->finishSuccess();
        }

        $output->writeln(['', '<fg=green>Fixture EXPORT success!</>', '']);
        return 0;
    }

}
