<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;

class PhpConfigGenerator
{

    private FileGenerator $fileGenerator;

    public function __construct(private string $configFile, private string $template)
    {
        $this->fileGenerator = new FileGenerator();
    }

    public function appendCode(string $code/*, string $hasCode*/): void
    {
        $fileGenerator = $this->fileGenerator;
        $fs = new Filesystem();
        $configFile = $this->configFile;
        if (!$fs->exists($configFile)) {
            $configFileTemplate = $this->template;
            $fileGenerator->generatePhpFile($configFile, $configFileTemplate);
        }

//        if (!$fileGenerator->hasCode($configFile, $hasCode)) {
            $fileGenerator->appendCodeInFile($configFile, $code);
//        }
    }

    public function hasCode(string $code): bool {
        return $this->fileGenerator->hasCode($this->configFile, $code);
    }
}