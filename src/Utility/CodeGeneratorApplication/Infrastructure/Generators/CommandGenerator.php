<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class CommandGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;
    private FileGenerator $fileGenerator;

    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();
        $this->fileGenerator = new FileGenerator();
    }

    public function generate(GenerateApplicationCommand $command): GenerateResult
    {
        $commandClassName = ApplicationPathHelper::getCommandClass($command);

        $params = [
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = __DIR__ . '/../../resources/templates/command.tpl.php';

//        $fileGenerator = new FileGenerator();
//        $fileName = $fileGenerator->generatePhpClass($commandClassName, $template, $params);

        $code = $this->codeGenerator->generatePhpClassCode($commandClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($commandClassName);
        $this->fs->dumpFile($fileName, $code);

//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}