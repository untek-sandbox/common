<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Helpers;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;

class GeneratorHelper
{

    public static function generate(array $generators, object $command): GenerateResultCollection
    {
        $collection = new GenerateResultCollection();
        foreach ($generators as $generator) {
            /** @var GeneratorInterface $generator */
            $resultCollection = $generator->generate($command);
            $collection->merge($resultCollection);
        }
        return $collection;
    }

    public static function dump(GenerateResultCollection $collection): void
    {
        $fs = new Filesystem();
        foreach ($collection->getAll() as $result) {
            if($result instanceof FileResult) {
                $fs->dumpFile($result->getName(), $result->getContent());
            }
        }
    }
}
