<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Component\Render\Infrastructure\Services\Render;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Core\Instance\Helpers\ClassHelper;

class CodeGenerator
{

    public function generatePhpCode(string $template, array $parameters = []): string
    {
        $code = $this->generateCode($template, $parameters);
        $code = '<?php' . PHP_EOL . PHP_EOL . trim($code);
        return $code;
    }

    public function generatePhpClassCode(string $className, string $template, array $parameters = []): string
    {
        $parameters['namespace'] = ClassHelper::getNamespace($className);
        $parameters['className'] = ClassHelper::getClassOfClassName($className);
//        $fileName = PackageHelper::pathByNamespace($className) . '.php';
        $code = $this->generatePhpCode($template, $parameters);
        return $code;
    }

    public function generateCode(string $template, array $parameters = []): string
    {
        $render = new Render();
        $code = $render->renderFile($template, $parameters);
        return $code;
    }

    public function appendCodeInFile(string $fileName, string $codeForAppend): string
    {
        $fs = new Filesystem();
        $code = file_get_contents($fileName);
        $code = trim($code);
        $codeLines = explode(PHP_EOL, $code);
        $lastLine = array_pop($codeLines);
        $codeLines[] = '';
        $codeLines[] = $codeForAppend;
        $codeLines[] = $lastLine;
        $codeLines[] = '';
        $code = implode(PHP_EOL, $codeLines);
        return $code;
    }
}