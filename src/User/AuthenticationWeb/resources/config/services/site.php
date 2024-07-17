<?php

use Untek\User\AuthenticationWeb\Presentation\Http\Site\Controllers\UserAuthController;
use Untek\User\AuthenticationWeb\Presentation\Http\Site\Controllers\UserLogoutController;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Untek\Component\Web\View\Libs\View;
use Untek\Component\Web\Form\Libs\FormManager;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrServiceInterface;
use Untek\FrameworkPlugin\HttpAuthentication\Application\Services\WebAuthentication;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(UserAuthController::class, UserAuthController::class)
        ->args([
            service(View::class),
            service(FormManager::class),
            service(WebAuthentication::class),
            service(CommandBusInterface::class),
            service(IdentityRepositoryInterface::class),
            service(ToastrServiceInterface::class),
            service(TokenStorageInterface::class),
        ])
        ->tag('http.controller', [
            'name' => 'user-sign-in',
            'path' => '/user/sign-in',
            'methods' => ['GET', 'POST'],
        ])
    ;

    $services->set(UserLogoutController::class, UserLogoutController::class)
        ->args([
            service(View::class),
            service(FormManager::class),
            service(WebAuthentication::class),
            service(CommandBusInterface::class),
            service(IdentityRepositoryInterface::class),
            service(ToastrServiceInterface::class),
            service(TokenStorageInterface::class),
        ])
        ->tag('http.controller', [
            'name' => 'user-sign-out',
            'path' => '/user/sign-out',
            'methods' => ['POST'],
        ])
    ;
};