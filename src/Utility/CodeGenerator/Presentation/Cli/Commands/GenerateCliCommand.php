<?php

namespace Untek\Utility\CodeGenerator\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Model\Cqrs\CommandBusInterface;

class GenerateCliCommand extends Command
{

    protected CommandBusInterface $bus;
    protected array $interacts;

    public function __construct(CommandBusInterface $bus, string $name = null, array $interacts)
    {
        parent::__construct($name);
        $this->bus = $bus;
        $this->interacts = $interacts;
    }

    protected function input(SymfonyStyle $io): array
    {
        $commands = [];
        foreach ($this->interacts as $interact) {
            $commands1 = $interact->input($io);
            if ($commands1) {
                $commands = ArrayHelper::merge($commands, $commands1);
            }
        }
        return $commands;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
//        $io->title('Code generator');
        $commands = $this->input($io);
        if ($commands) {
            $this->handleCommands($commands);
            $io->success('Code generated successfully');
        }
        return Command::SUCCESS;
    }

    protected function handleCommands(array $commands): array
    {
        $result = [];
        foreach ($commands as $command) {
            $result[] = $this->bus->handle($command);
        }
        return $result;
    }
}
