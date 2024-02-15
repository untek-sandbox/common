<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;

class ContainerLoadConfigGenerator
{

    public function __construct(protected GenerateResultCollection $collection, private string $namespace)
    {
    }

    public function generate(string $modulePath): GenerateResultCollection
    {
        $codeForAppend = '$loader->load(__DIR__ . \'/../' . $modulePath . '\');';
        $configFile = __DIR__ . '/../../../../../../../../config/container.php';
        $templateFile = __DIR__ . '/../../resources/templates/container-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($this->collection, $configFile, $templateFile);
        $resultCollection = new GenerateResultCollection();
        if (!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend . PHP_EOL);
            $this->collection->add(new FileResult($configFile, $code));
        }
        return $resultCollection;
    }

}