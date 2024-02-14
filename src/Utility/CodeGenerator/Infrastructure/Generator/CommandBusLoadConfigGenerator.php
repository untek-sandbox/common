<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;

class CommandBusLoadConfigGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;
//    private FileGenerator $fileGenerator;

    public function __construct(private string $namespace)
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();
//        $this->fileGenerator = new FileGenerator();
    }

    public function generate(string $modulePath): string {
        $codeForAppend = '    $configLoader->boot(__DIR__ . \'/../'.$modulePath.'\');';
        $configFile = __DIR__ . '/../../../../../../../../config/command-bus.php';
        $templateFile = __DIR__ . '/../../resources/templates/command-bus-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->dump($configFile, $code);
        }
        return $configFile;
    }

    protected function dump(string $fileName, string $code): GenerateResult
    {
        $this->fs->dumpFile($fileName, $code);
        $generateResult = new GenerateResult($fileName, $code);
        return $generateResult;
    }

}