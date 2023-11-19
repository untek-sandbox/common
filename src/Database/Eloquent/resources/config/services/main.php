<?php

use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Eloquent\Domain\Factories\ManagerFactory;
use Illuminate\Database\Capsule\Manager as CapsuleManager;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

/*[
    Manager::class => function () {
        return ManagerFactory::createManagerFromEnv();
    },
    CapsuleManager::class => Manager::class,
];*/

    $services->set(Manager::class, Manager::class)
        ->factory([ManagerFactory::class, 'createManagerFromEnv']);


    $services->alias(CapsuleManager::class, Manager::class);
};