<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CommandBusLoadConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigBusImportGenerator
{

    public function generate(GenerateApplicationCommand $command): GenerateResult
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClassName($command);

        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $fs = new Filesystem();
        $relative = $fs->makePathRelative($path, getenv('ROOT_DIRECTORY'));

        $modulePath = $relative.'resources/config/command-bus.php';

        $consoleLoadConfigGenerator = new CommandBusLoadConfigGenerator($command->getNamespace());
        $fileName = $consoleLoadConfigGenerator->generate($modulePath);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}