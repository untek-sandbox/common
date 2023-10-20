<?php

namespace Untek\Utility\CodeGenerator\Presentation\Cli\Commands;

use Untek\Model\Cqrs\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;

abstract class AbstractCommand extends Command
{

    protected CommandBusInterface $bus;

    abstract protected function input(SymfonyStyle $io): array;

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