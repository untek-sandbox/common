<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Component\App\Services\ControllerAccessChecker;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\RestApiPathHelper;

class ContainerConfigGenerator
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateRestApiCommand $command): void
    {
        $controllerClassName = RestApiPathHelper::getControllerClass($command);
        $args = [
            '\\'.CommandBusInterface::class,
            UrlGeneratorInterface::class,
            ControllerAccessChecker::class,
        ];
        $consoleConfigGenerator = new \Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator($this->collection, $command->getNamespace());
        $consoleConfigGenerator->generate($controllerClassName, $controllerClassName, $args);
    }
}