<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Core\FileSystem\Helpers\FilePathHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerLoadConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigImportGenerator
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateApplicationCommand $command): GenerateResultCollection
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClassName($command);
        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        if (realpath($path) === false) {
            $up = FilePathHelper::up($path);
            $basename = basename($path);
            $path = $up . '/' . $basename;
        }
        $relative = GeneratorFileHelper::fileNameTotoRelative($path);
        $modulePath = $relative . '/resources/config/services/main.php';
        $consoleLoadConfigGenerator = new ContainerLoadConfigGenerator($this->collection, $command->getNamespace());
        return $consoleLoadConfigGenerator->generate($modulePath);
    }
}