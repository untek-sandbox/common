<?php

namespace Untek\Utility\CodeGenerator\Presentation\Cli\Commands;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Model\Cqrs\CommandBusInterface;
use Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateApplicationInteract;

class GenerateApplicationCliCommand extends AbstractCommand
{

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
            if($commands1) {
                $commands = ArrayHelper::merge($commands, $commands1);
            }
        }
        return $commands;
    }
}
