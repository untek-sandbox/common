<?php

use Untek\User\Authentication\Presentation\Http\RestApi\Controllers\GenerateTokenByPasswordController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes
        ->add('user/authentication/generate-token-by-password', '/generate-token-by-password')
        ->controller(GenerateTokenByPasswordController::class)
        ->methods(['POST']);
};