<?php

namespace Untek\Utility\CodeGenerator\Presentation\Cli\Commands;

use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Model\Cqrs\CommandBusInterface;
use Untek\Utility\CodeGenerator\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\Application\Helpers\CommandHelper;
use Untek\Utility\CodeGenerator\Presentation\Libs\Validator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Framework\Console\Symfony4\Question\ChoiceQuestion;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;

class GenerateRestApiCliCommand extends AbstractCommand
{

    public function __construct(CommandBusInterface $bus, string $name = null)
    {
        parent::__construct($name);
        $this->bus = $bus;
    }


}
