<?php

namespace Untek\Utility\CodeGenerator\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Model\Cqrs\CommandBusInterface;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;

class GenerateCodeCommand extends Command
{

    public function __construct(
        string $name = null,
        private CommandBusInterface $bus,
        private array $interacts
    )
    {
        parent::__construct($name);
    }

    protected function input(SymfonyStyle $io): array
    {
        $commands = [];
        foreach ($this->interacts as $interact) {
            /** @var InteractInterface $interact */
            $interactCommands = $interact->input($io);
            if ($interactCommands) {
                $commands = ArrayHelper::merge($commands, $interactCommands);
            }
        }
        return $commands;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $commands = $this->input($io);
        if ($commands) {
            $files = $this->handleCommands($commands);
            $io->newLine();
            $io->writeln('Generated files:');
            $io->listing($files);
            $io->success('Code generated successfully');
        }
        return Command::SUCCESS;
    }

    protected function handleCommands(array $commands): array
    {
        $result = [];
        foreach ($commands as $command) {
            $handleResult = $this->bus->handle($command);
            if ($handleResult) {
                $result = ArrayHelper::merge($result, $handleResult);
            }
        }
        return $result;
    }
}
