<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Untek\Component\Render\Infrastructure\Services\Render;
use Untek\Core\Code\Helpers\PackageHelper;
use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Instance\Helpers\ClassHelper;

class FileGenerator
{

    public function __construct()
    {
    }

    public function generatePhpClass(string $className, string $template, array $parameters = []): string
    {
        $parameters['namespace'] = ClassHelper::getNamespace($className);
        $parameters['className'] = ClassHelper::getClassOfClassName($className);
        $fileName = PackageHelper::pathByNamespace($className) . '.php';
        $this->generatePhpFile($fileName, $template, $parameters);
        return $fileName;
    }

    public function generatePhpFile(string $fileName, string $template, array $parameters = [])
    {
        $render = new Render();
        $code = $render->renderFile($template, $parameters);
        $code = '<?php' . PHP_EOL . PHP_EOL . trim($code);
        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($fileName, $code);
    }

    public function appendCodeInFile(string $fileName, string $codeForAppend): void
    {
        $fs = new Filesystem();
        $code = file_get_contents($fileName);
        $code = trim($code);
        $codeLines = explode(PHP_EOL, $code);
        $lastLine = array_pop($codeLines);
        $codeLines[] = '';
        $codeLines[] = "\t" . trim($codeForAppend);
        $codeLines[] = $lastLine;
        $code = implode(PHP_EOL, $codeLines);
        $fs->dumpFile($fileName, $code);
    }

    public function hasCode(string $fileName, string $needle): bool
    {
        if(!is_file($fileName)) {
            return false;
        }
        $code = file_get_contents($fileName);
        return str_contains($code, $needle);
    }
}