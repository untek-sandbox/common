<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Helpers;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\PackageHelper;

class GeneratorFileHelper
{

    public static function getFileNameByClass(string $className): string
    {
        $fileName = PackageHelper::pathByNamespace($className) . '.php';
        return $fileName;
    }

    public static function fileNameTotoRelative(string $filename): string
    {
        $fs = new Filesystem();
        if($fs->isAbsolutePath($filename)) {
            $filename = str_replace(getenv('ROOT_DIRECTORY'), '', $filename);
//            dd($filename);
        }
//        $filename = $fs->makePathRelative(realpath($filename), getenv('ROOT_DIRECTORY'));
        return rtrim($filename, '/');
    }
}