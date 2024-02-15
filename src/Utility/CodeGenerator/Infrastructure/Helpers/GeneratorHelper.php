<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Helpers;

use Untek\Utility\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;

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
}
