<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\User\AuthenticationWeb\Presentation\Http\Site\Controllers\UserAuthController;
use Untek\User\AuthenticationWeb\Presentation\Http\Site\Controllers\UserLogoutController;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire();

    $services->set(UserAuthController::class, UserAuthController::class)
        ->tag('http.controller', [
            'name' => 'user-sign-in',
            'path' => '/user/sign-in',
            'methods' => ['GET', 'POST'],
        ]);

    $services->set(UserLogoutController::class, UserLogoutController::class)
        ->tag('http.controller', [
            'name' => 'user-sign-out',
            'path' => '/user/sign-out',
            'methods' => ['POST'],
        ]);
};