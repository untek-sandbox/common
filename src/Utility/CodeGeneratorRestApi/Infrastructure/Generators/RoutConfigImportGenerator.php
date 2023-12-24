<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generator\RoutesLoadConfigGenerator;

class RoutConfigImportGenerator
{

    public function generate(GenerateRestApiCommand $command): GenerateResult
    {
        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $fs = new Filesystem();
        $relative = $fs->makePathRelative($path, getenv('ROOT_DIRECTORY'));

        $modulePath = $relative . 'resources/config/rest-api/v' . $command->getVersion() . '-routes.php';

        $consoleLoadConfigGenerator = new RoutesLoadConfigGenerator($command->getNamespace());
        $fileName = $consoleLoadConfigGenerator->generate($modulePath, '/v' . $command->getVersion());

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}