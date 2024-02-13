<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Untek\Component\Render\Infrastructure\Services\Render;
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
        $code = $this->generatePhpCode($template, $parameters);
        return $code;
    }

    public function generateCode(string $template, array $parameters = []): string
    {
        $render = new Render();
        $code = $render->renderFile($template, $parameters);
        return $code;
    }

    public function appendCode(string $code, string $codeForAppend): string
    {
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