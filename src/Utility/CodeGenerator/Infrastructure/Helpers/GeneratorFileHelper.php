<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Helpers;

use Symfony\Component\Filesystem\Filesystem;

class GeneratorFileHelper
{

    public static function fileNameTotoRelative(string $filename): string
    {
        $fs = new Filesystem();
        $fileName = $fs->makePathRelative(realpath($filename), getenv('ROOT_DIRECTORY'));
        return rtrim($fileName, '/');
    }
}