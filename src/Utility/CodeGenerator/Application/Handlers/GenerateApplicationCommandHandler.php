<?php

namespace Untek\Utility\CodeGenerator\Application\Handlers;

use Untek\Utility\CodeGenerator\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\Application\Enums\TypeEnum;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\TypeGenerator;
use Untek\Core\Text\Helpers\Inflector;

class GenerateApplicationCommandHandler
{

    public function __invoke(GenerateApplicationCommand $command)
    {
        $this->generateCommandClass($command);
        $this->generateCommandHandlerClass($command);
        $this->generateCommandValidatorClass($command);
    }

    private function generateCommandValidatorClass(GenerateApplicationCommand $command) {
        $validatorClassName = $this->getCommandValidatorClass($command);
        $commandClassName = $this->getCommandClass($command);

        $params = [
            'properties' => $this->prepareProperties($command),
            'commandClassName' => $commandClassName,
        ];
        $template = __DIR__ . '/../../Resources/templates/validator.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileGenerator->generatePhpClass($validatorClassName, $template, $params);
    }

    private function getCommandValidatorClass(GenerateApplicationCommand $command): string {
        $camelizeName = Inflector::camelize($command->getName());
        $camelizeUnitName = $camelizeName . Inflector::camelize($command->getType());
        $validatorClassName = $command->getNamespace() . '\\Application\\Validators\\' . $camelizeUnitName . 'Validator';
        return $validatorClassName;
    }

    private function prepareProperties(GenerateApplicationCommand $command): array {
        $properties = [];
        foreach ($command->getProperties() as &$commandAttribute) {
            $name = Inflector::variablize($commandAttribute['name']);
            $propertyGenerator = new PropertyGenerator($name, '', PropertyGenerator::FLAG_PRIVATE, TypeGenerator::fromTypeString($commandAttribute['type']));
            $propertyGenerator->omitDefaultValue();
            $properties[] = $propertyGenerator;
        }
        return $properties;
    }

    private function generateCommandClass(GenerateApplicationCommand $command) {
        $commandClassName = $this->getCommandClass($command);

        $params = [
            'properties' => $this->prepareProperties($command),
        ];
        $template = __DIR__ . '/../../Resources/templates/command.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileGenerator->generatePhpClass($commandClassName, $template, $params);
    }

    private function generateCommandHandlerClass(GenerateApplicationCommand $command) {
        $camelizeName = Inflector::camelize($command->getName());
        $camelizeUnitName = $camelizeName . Inflector::camelize($command->getType());
        $handlerClassName = $command->getNamespace() . '\\Application\\Handlers\\' . $camelizeUnitName . 'Handler';
        $commandClassName = $this->getCommandClass($command);
        $validatorClassName = $this->getCommandValidatorClass($command);

        $params = [
            'commandClassName' => $commandClassName,
            'validatorClassName' => $validatorClassName,
        ];
        $template = __DIR__ . '/../../Resources/templates/handler.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileGenerator->generatePhpClass($handlerClassName, $template, $params);
    }

    private function getCommandClass(GenerateApplicationCommand $command): string {
        $camelizeName = Inflector::camelize($command->getName());
        $camelizeUnitName = $camelizeName . Inflector::camelize($command->getType());
        $commandClassName = $command->getNamespace() . '\\Application\\Commands\\' . $camelizeUnitName;
        if ($command->getType() == TypeEnum::QUERY) {
            $commandClassName = $command->getNamespace() . '\\Application\\Queries\\' . $camelizeUnitName;
        }
        return $commandClassName;
    }
}