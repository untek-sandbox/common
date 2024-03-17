<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var PropertyGenerator[] $properties
 */

use Laminas\Code\Generator\PropertyGenerator;

?>

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();
};
