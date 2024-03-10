<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigBusImportGenerator
{

    private string $template = __DIR__ . '/../../resources/templates/command-bus-load-config.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateApplicationCommand $command): void
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClass($command);
        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $relative = GeneratorFileHelper::fileNameTotoRelative($path);
        $modulePath = $relative . '/resources/config/command-bus.php';
        $codeForAppend = '    $configLoader->boot(__DIR__ . \'/..' . $modulePath . '\');';
        $fileName = __DIR__ . '/../../../../../../../../config/command-bus.php';
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $configGenerator = new PhpConfigGenerator($this->collection, $fileName, $template);
        if (!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->collection->add(new FileResult($fileName, $code));
        }
    }
}