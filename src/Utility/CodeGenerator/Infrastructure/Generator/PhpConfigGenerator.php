<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Symfony\Component\Filesystem\Filesystem;

class PhpConfigGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct(private string $configFile, private string $template)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function appendCode(string $codeForAppend): string
    {
        $fs = new Filesystem();
        if (!$fs->exists($this->configFile)) {
            $code = $this->codeGenerator->generatePhpCode($this->template);
        } else {
            $code = file_get_contents($this->configFile);
        }
        $code = $this->codeGenerator->appendCode($code, $codeForAppend);
        return $code;
    }

    public function hasCode(string $code): bool
    {
        return $this->hasCodeInFile($this->configFile, $code);
    }

    public function hasCodeInFile(string $fileName, string $needle): bool
    {
        if (!is_file($fileName)) {
            return false;
        }
        $code = file_get_contents($fileName);
        return str_contains($code, $needle);
    }
}