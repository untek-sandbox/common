<?php

use Illuminate\Database\Capsule\Manager as CapsuleManager;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Eloquent\Domain\Factories\ManagerFactory;
use Untek\Database\Eloquent\Domain\Orm\EloquentOrm;
use Untek\Model\EntityManager\Interfaces\TransactionInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire();

    $services->set(EloquentOrm::class);
    $services->alias(TransactionInterface::class, EloquentOrm::class);

    $services->set(Manager::class)
        ->factory([ManagerFactory::class, 'createManagerFromEnv']);


    $services->alias(CapsuleManager::class, Manager::class);
};