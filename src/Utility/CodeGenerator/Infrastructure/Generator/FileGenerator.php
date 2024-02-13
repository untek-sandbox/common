<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Untek\Component\Render\Infrastructure\Services\Render;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Code\Helpers\PackageHelper;
use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Instance\Helpers\ClassHelper;

class FileGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;

    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();
    }

    public function generatePhpClassFileName(string $className): string
    {
        $fileName = PackageHelper::pathByNamespace($className) . '.php';
        return $fileName;
    }

    public function generatePhpClass(string $className, string $template, array $parameters = []): string
    {
        $code = $this->codeGenerator->generatePhpClassCode($className, $template, $parameters);
//        $parameters['namespace'] = ClassHelper::getNamespace($className);
//        $parameters['className'] = ClassHelper::getClassOfClassName($className);
        $fileName = PackageHelper::pathByNamespace($className) . '.php';
//        $this->generatePhpFile($fileName, $template, $parameters);
        $this->fs->dumpFile($fileName, $code);
        return $fileName;
    }

    /**
     * @param string $fileName
     * @param string $template
     * @param array $parameters
     * @deprecated
     */
    public function generatePhpFile(string $fileName, string $template, array $parameters = [])
    {
        DeprecateHelper::hardThrow();
//        $render = new Render();
//        $code = $render->renderFile($template, $parameters);
//        $code = $this->codeGenerator->generateCode($template, $parameters);
//        $code = '<?php' . PHP_EOL . PHP_EOL . trim($code);
        $code = $this->codeGenerator->generatePhpCode($template, $parameters);
        $this->fs->dumpFile($fileName, $code);
    }

    public function generateFile(string $fileName, string $template, array $parameters = [])
    {
        DeprecateHelper::hardThrow();
        $code = $this->codeGenerator->generateCode($template, $parameters);
        $this->fs->dumpFile($fileName, $code);
    }

    public function hasCode(string $fileName, string $needle): bool
    {
        DeprecateHelper::hardThrow();
        if(!is_file($fileName)) {
            return false;
        }
        $code = file_get_contents($fileName);
        return str_contains($code, $needle);
    }
}