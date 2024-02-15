<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\FileSystem\Helpers\FileHelper;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;

class PhpConfigGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct(protected GenerateResultCollection $collection, private string $configFile, private string $template)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function appendCode(string $codeForAppend): string
    {
        $code = $this->getCode($this->configFile);
        if (empty($code)) {
            $code = $this->codeGenerator->generatePhpCode($this->template);
        }
        $code = $this->codeGenerator->appendCode($code, $codeForAppend);
        return $code;
    }

    protected function getCode(string $name): ?string
    {
        $name = FileHelper::normalizePath($name);
        $fs = new Filesystem();
        if ($this->collection->has($name)) {
            $result = $this->collection->get($name);
            $code = $result->getContent();
        } elseif ($fs->exists($name)) {
            $code = file_get_contents($name);
        } else {
            $code = null;
        }
        return $code;
    }

    public function hasCode(string $code): bool
    {
        return $this->hasCodeInFile($this->configFile, $code);
    }

    public function hasCodeInFile(string $fileName, string $needle): bool
    {
        $code = $this->getCode($fileName);
        if (empty($code)) {
            return false;
        }
        return str_contains($code, $needle);
    }
}