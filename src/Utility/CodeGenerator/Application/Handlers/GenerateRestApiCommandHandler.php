<?php

namespace Untek\Utility\CodeGenerator\Application\Handlers;

use Untek\Utility\CodeGenerator\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\Application\Helpers\CommandHelper;
use Untek\Utility\CodeGenerator\Application\Validators\GenerateRestApiCommandValidator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class GenerateRestApiCommandHandler
{

    /**
     * @param GenerateRestApiCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateRestApiCommand $command)
    {
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
        $fileGenerator->generatePhpClass($controllerClassName, $template, $params);

        $this->generateContainerConfig($command);
        $this->generateRoutConfig($command);

        // TODO: генерить авто-тест
    }

    private function generateContainerConfig(GenerateRestApiCommand $command): void
    {
        $controllerClassName = $this->getControllerClassName($command);
        $fileGenerator = new FileGenerator();
        $fs = new Filesystem();
        $configFile = ComposerHelper::getPsr4Path($command->getNamespace()) . '/Resources/config/services/main.php';
        if (!$fs->exists($configFile)) {
            $configFileTemplate = __DIR__ . '/../../Resources/templates/container-config.tpl.php';
            $fileGenerator->generatePhpFile($configFile, $configFileTemplate);
        }

        if (!$fileGenerator->hasCode($configFile, $controllerClassName)) {
            $controllerDefinition =
                '    $services->set(\\' . $controllerClassName . '::class, \\' . $controllerClassName . '::class)
        ->args([
            service(\Untek\Model\Cqrs\CommandBusInterface::class),
            service(\Symfony\Component\Routing\Generator\UrlGeneratorInterface::class),
        ]);';
            $fileGenerator->appendCodeInFile($configFile, $controllerDefinition);
        }
    }

    private function generateRoutConfig(GenerateRestApiCommand $command): void
    {
        $controllerClassName = $this->getControllerClassName($command);
        $fileGenerator = new FileGenerator();
        $fs = new Filesystem();
        $configFile = ComposerHelper::getPsr4Path($command->getNamespace()) . '/Resources/config/rest-api-routes.php';
        if (!$fs->exists($configFile)) {
            $configFileTemplate = __DIR__ . '/../../Resources/templates/route-config.tpl.php';
            $fileGenerator->generatePhpFile($configFile, $configFileTemplate);
        }

        if (!$fileGenerator->hasCode($configFile, $controllerClassName)) {
            $controllerDefinition =
                '    $routes
        ->add(\'' . $command->getUri() . '\', \'/' . $command->getUri() . '\')
        ->controller(\\' . $controllerClassName . '::class)
        ->methods([\'' . $command->getHttpMethod() . '\']);';
            $fileGenerator->appendCodeInFile($configFile, $controllerDefinition);
        }
    }

    private function getControllerClassName(GenerateRestApiCommand $command): string
    {
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($endCommandClassName));
        return $command->getNamespace() . '\\Presentation\\Http\\RestApi\\Controllers\\' . $pureCommandClassName . 'Controller';
    }
}