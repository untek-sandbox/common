<?php

namespace Untek\Utility\CodeGeneratorRestApi\Application\Handlers;

use Untek\Utility\CodeGenerator\Infrastructure\Generator\CommandBusLoadConfigGenerator;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Application\Helpers\CommandHelper;
use Untek\Utility\CodeGeneratorRestApi\Application\Validators\GenerateRestApiCommandValidator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generator\RoutesLoadConfigGenerator;

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

        $files[] = $this->generateController($command);
        $files[] = $this->generateControllerTest($command);
        $files[] = $this->generateContainerConfig($command);
        $files[] = $this->generateRoutConfig($command);
        $files[] = $this->generateRoutLoadConfig($command);
        $files[] = 'Endpoint: '.$command->getHttpMethod().' rest-api/' . $command->getVersion() . '/' . $command->getUri();

        return $files;
    }

    private function generateControllerTest(GenerateRestApiCommand $command): string {
        $controllerTestClassName = $this->getControllerTestClassName($command);
        $params = [
            'endpoint' => '/' . $command->getVersion() . '/' . $command->getUri(),
            'method' => $command->getHttpMethod(),
        ];
        $template = __DIR__ . '/../../resources/templates/rest-api-controller-test.tpl.php';

        $fileGenerator = new FileGenerator();
        return $fileGenerator->generatePhpClass($controllerTestClassName, $template, $params);
    }

    private function generateController(GenerateRestApiCommand $command): string {
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $controllerClassName = $this->getControllerClassName($command);

        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $command->getCommandClass(),
        ];
        $template = __DIR__ . '/../../resources/templates/rest-api-controller.tpl.php';

        $fileGenerator = new FileGenerator();
        return $fileGenerator->generatePhpClass($controllerClassName, $template, $params);
    }

    private function generateContainerConfig(GenerateRestApiCommand $command): string
    {
        $controllerClassName = $this->getControllerClassName($command);

        $args = [
            'service(\Untek\Model\Cqrs\CommandBusInterface::class)',
            'service(\Symfony\Component\Routing\Generator\UrlGeneratorInterface::class)'
        ];
        $consoleConfigGenerator = new ContainerConfigGenerator($command->getNamespace());
        $configFile = $consoleConfigGenerator->generate($controllerClassName, $controllerClassName, $args);

        return $configFile;
    }

    private function generateRoutConfig(GenerateRestApiCommand $command): string
    {
        $controllerClassName = $this->getControllerClassName($command);

        $configFile = ComposerHelper::getPsr4Path($command->getNamespace()) . '/resources/config/rest-api/'.$command->getVersion().'-routes.php';
        $templateFile = __DIR__ . '/../../resources/templates/route-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);

        if(!$configGenerator->hasCode($controllerClassName)) {
            $routeName = $command->getHttpMethod() . '_' . $command->getUri();
            $controllerDefinition =
                '    $routes
        ->add(\'' . $routeName . '\', \'/' . $command->getUri() . '\')
        ->controller(\\' . $controllerClassName . '::class)
        ->methods([\'' . $command->getHttpMethod() . '\']);';
            $configGenerator->appendCode($controllerDefinition);
        }

        return $configFile;
    }

    private function generateRoutLoadConfig(GenerateRestApiCommand $command): string
    {
        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $fs = new Filesystem();
        $relative = $fs->makePathRelative($path, getenv('ROOT_DIRECTORY'));

        $modulePath = $relative.'resources/config/rest-api/'.$command->getVersion().'-routes.php';

        $consoleLoadConfigGenerator = new RoutesLoadConfigGenerator($command->getNamespace());
        $configFile = $consoleLoadConfigGenerator->generate($modulePath, '/' . $command->getVersion());

        return $configFile;
    }

    private function getControllerClassName(GenerateRestApiCommand $command): string
    {
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($endCommandClassName));
        return $command->getNamespace() . '\\Presentation\\Http\\RestApi\\Controllers\\' . $pureCommandClassName . 'Controller';
    }

    private function getControllerTestClassName(GenerateRestApiCommand $command): string
    {
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($endCommandClassName));
        return 'Tests\\RestApi\\'.$command->getModuleName().'\\' . $pureCommandClassName . 'Test';
    }
}