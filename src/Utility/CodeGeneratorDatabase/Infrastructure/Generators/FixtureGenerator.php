<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\DatabasePathHelper;

class FixtureGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $seedClassName = DatabasePathHelper::getSeedClass($command);
        $mainFileName = realpath(__DIR__ . '/../../../../../../../..') . '/resources/seeds/' . $command->getTableName() . '.php';
        $testFileName = realpath(__DIR__ . '/../../../../../../../..') . '/tests/fixtures/seeds/' . $command->getTableName() . '.php';
        $params = [
            'seedClassName' => $seedClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/fixture.tpl.php';
        $code = $this->codeGenerator->generatePhpCode($template, $params);
        $this->collection->add(new FileResult($mainFileName, $code));
        $this->collection->add(new FileResult($testFileName, $code));
    }
}