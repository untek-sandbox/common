<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;

class PhpConfigGenerator
{

    private FileGenerator $fileGenerator;
    private CodeGenerator $codeGenerator;

    public function __construct(private string $configFile, private string $template)
    {
        $this->fileGenerator = new FileGenerator();
        $this->codeGenerator = new CodeGenerator();
    }

    public function appendCode(string $code/*, string $hasCode*/): string
    {
        $fs = new Filesystem();
        $configFile = $this->configFile;
        if (!$fs->exists($configFile)) {
            $this->fileGenerator->generatePhpFile($configFile, $this->template);
        }
//        $this->fileGenerator->appendCodeInFile($configFile, $code);
        $code = $this->codeGenerator->appendCodeInFile($configFile, $code);
        return $code;
    }

    public function generateCode(string $code): string
    {

    }

    public function hasCode(string $code): bool {
        return $this->fileGenerator->hasCode($this->configFile, $code);
    }
}