<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Handlers;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CommandBusLoadConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerLoadConfigGenerator;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\TypeGenerator;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;

class GenerateApplicationCommandHandler
{

    public function __invoke(GenerateApplicationCommand $command)
    {
        $files = [];
        $files[] = $this->generateCommandClass($command);
        $files[] = $this->generateCommandHandlerClass($command);
        $files[] = $this->generateCommandValidatorClass($command);
        $files[] = $this->generateContainerConfig($command);
        $files[] = $this->generateContainerLoadConfig($command);
        $files[] = $this->generateCommandBusConfig($command);
        $files[] = $this->generateCommandBusLoadConfig($command);
        return $files;
    }

    private function generateContainerConfig(GenerateApplicationCommand $command): string
    {
        $handlerClassName = $this->getHandlerClassName($command);

        $handlerDefinition =
            '    $services->set(\\' . $handlerClassName . '::class, \\' . $handlerClassName . '::class);';
        $consoleConfigGenerator = new ContainerConfigGenerator($command->getNamespace());
        $configFile = $consoleConfigGenerator->generate($handlerDefinition, $handlerClassName);

        return $configFile;
    }

    private function generateContainerLoadConfig(GenerateApplicationCommand $command): string
    {
        $handlerClassName = $this->getHandlerClassName($command);

        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $fs = new Filesystem();
        $relative = $fs->makePathRelative($path, getenv('ROOT_DIRECTORY'));

        $modulePath = $relative.'resources/config/services/main.php';

        $consoleLoadConfigGenerator = new ContainerLoadConfigGenerator($command->getNamespace());
        $configFile = $consoleLoadConfigGenerator->generate($modulePath);

        return $configFile;
    }

    private function generateCommandBusConfig(GenerateApplicationCommand $command): string
    {
        $handlerClassName = $this->getHandlerClassName($command);
        $commandClassName = $this->getCommandClass($command);

        $configFile = ComposerHelper::getPsr4Path($command->getNamespace()) . '/resources/config/command-bus.php';
        $templateFile = __DIR__ . '/../../resources/templates/command-bus-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);

        if(!$configGenerator->hasCode($handlerClassName)) {
            $controllerDefinition =
                '    $configurator->define(\\' . $commandClassName . '::class, \\' . $handlerClassName . '::class);';
            $configGenerator->appendCode($controllerDefinition);
        }

        return $configFile;
    }

    private function generateCommandBusLoadConfig(GenerateApplicationCommand $command): string
    {
        $handlerClassName = $this->getHandlerClassName($command);

        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $fs = new Filesystem();
        $relative = $fs->makePathRelative($path, getenv('ROOT_DIRECTORY'));

        $modulePath = $relative.'resources/config/command-bus.php';

        $consoleLoadConfigGenerator = new CommandBusLoadConfigGenerator($command->getNamespace());
        $configFile = $consoleLoadConfigGenerator->generate($modulePath);

        return $configFile;
    }

    private function generateCommandValidatorClass(GenerateApplicationCommand $command): string {
        $validatorClassName = $this->getCommandValidatorClass($command);
        $commandClassName = $this->getCommandClass($command);

        $params = [
            'properties' => $this->prepareProperties($command),
            'commandClassName' => $commandClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/validator.tpl.php';

        $fileGenerator = new FileGenerator();
        return $fileGenerator->generatePhpClass($validatorClassName, $template, $params);
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

    private function generateCommandClass(GenerateApplicationCommand $command): string {
        $commandClassName = $this->getCommandClass($command);

        $params = [
            'properties' => $this->prepareProperties($command),
        ];
        $template = __DIR__ . '/../../resources/templates/command.tpl.php';

        $fileGenerator = new FileGenerator();
        return $fileGenerator->generatePhpClass($commandClassName, $template, $params);
    }

    private function getHandlerClassName(GenerateApplicationCommand $command): string {
        $camelizeName = Inflector::camelize($command->getName());
        $camelizeUnitName = $camelizeName . Inflector::camelize($command->getType());
        $handlerClassName = $command->getNamespace() . '\\Application\\Handlers\\' . $camelizeUnitName . 'Handler';
        return $handlerClassName;
    }

    private function generateCommandHandlerClass(GenerateApplicationCommand $command): string {
        $handlerClassName = $this->getHandlerClassName($command);

        $commandClassName = $this->getCommandClass($command);
        $validatorClassName = $this->getCommandValidatorClass($command);

        $params = [
            'commandClassName' => $commandClassName,
            'validatorClassName' => $validatorClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/handler.tpl.php';

        $fileGenerator = new FileGenerator();
        return $fileGenerator->generatePhpClass($handlerClassName, $template, $params);
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