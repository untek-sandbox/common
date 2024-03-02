<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class CommandHandlerGenerator
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/handler.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateApplicationCommand $command): void
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClassName($command);
        $commandClassName = ApplicationPathHelper::getCommandClass($command);
        $validatorClassName = ApplicationPathHelper::getCommandValidatorClass($command);

        $params = [
            'commandClassName' => $commandClassName,
            'validatorClassName' => $validatorClassName,
            'modelName' => $command->getModelName(),
            'modelClass' => \Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\ApplicationPathHelper::getModelClass($command),
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpClassCode($handlerClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($handlerClassName);
        $this->collection->add(new FileResult($fileName, $code));
    }
}