<?php

namespace Untek\Utility\CodeGeneratorRestApi\Application\Handlers;

use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\Application\Helpers\CommandHelper;
use Untek\Utility\CodeGeneratorRestApi\Application\Validators\GenerateRestApiCommandValidator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;

class GenerateRestApiCommandHandler
{

    /**
     * @param GenerateRestApiCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateRestApiCommand $command)
    {
        $files = [];

        $validator = new GenerateRestApiCommandValidator();
        $validator->validate($command);

        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $controllerClassName = $this->getControllerClassName($command);

        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $command->getCommandClass(),
        ];
        $template = __DIR__ . '/../../Resources/templates/rest-api-controller.tpl.php';

        $fileGenerator = new FileGenerator();
        $files[] = $fileGenerator->generatePhpClass($controllerClassName, $template, $params);

        $files[] = $this->generateContainerConfig($command);
        $files[] = $this->generateRoutConfig($command);

        // TODO: генерить авто-тест

        return $files;
    }

    private function generateContainerConfig(GenerateRestApiCommand $command): string
    {
        $controllerClassName = $this->getControllerClassName($command);

        $controllerDefinition =
            '    $services->set(\\' . $controllerClassName . '::class, \\' . $controllerClassName . '::class)
        ->args([
            service(\Untek\Model\Cqrs\CommandBusInterface::class),
            service(\Symfony\Component\Routing\Generator\UrlGeneratorInterface::class),
        ]);';
        $consoleConfigGenerator = new ContainerConfigGenerator($command->getNamespace());
        $configFile = $consoleConfigGenerator->generate($controllerDefinition, $controllerClassName);

        return $configFile;
    }

    private function generateRoutConfig(GenerateRestApiCommand $command): string
    {
        $controllerClassName = $this->getControllerClassName($command);

        $configFile = ComposerHelper::getPsr4Path($command->getNamespace()) . '/Resources/config/rest-api-routes.php';
        $templateFile = __DIR__ . '/../../Resources/templates/route-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);

        if(!$configGenerator->hasCode($controllerClassName)) {
            $controllerDefinition =
                '    $routes
        ->add(\'' . $command->getUri() . '\', \'/' . $command->getUri() . '\')
        ->controller(\\' . $controllerClassName . '::class)
        ->methods([\'' . $command->getHttpMethod() . '\']);';
            $configGenerator->appendCode($controllerDefinition);
        }

        return $configFile;
    }

    private function getControllerClassName(GenerateRestApiCommand $command): string
    {
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($endCommandClassName));
        return $command->getNamespace() . '\\Presentation\\Http\\RestApi\\Controllers\\' . $pureCommandClassName . 'Controller';
    }
}