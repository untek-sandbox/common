<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;

DeprecateHelper::hardThrow();

/*class CommandBusLoadConfigGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;


    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();

    }

    public function generate(string $modulePath): string {
        $codeForAppend = '    $configLoader->boot(__DIR__ . \'/../'.$modulePath.'\');';
        $configFile = __DIR__ . '/../../../../../../../../config/command-bus.php';
        $templateFile = __DIR__ . '/../../resources/templates/command-bus-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->fs->dumpFile($fileName, $code);
        }
        return $configFile;
    }
}*/