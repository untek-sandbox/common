<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $parameters = $configurator->parameters();

    $services->alias(RoleHierarchyInterface::class, RoleHierarchy::class);
    $services->set(AccessDecisionManagerInterface::class, AccessDecisionManager::class)
        ->args(
            [
                [
                    service(AuthenticatedVoter::class),
                    service(RoleVoter::class),
                    service(RoleHierarchyVoter::class),
                ]
            ]
        );
    $services->set(AuthenticationTrustResolver::class, AuthenticationTrustResolver::class);
    $services->set(AuthenticatedVoter::class, AuthenticatedVoter::class)
        ->args(
            [
                service(AuthenticationTrustResolver::class),
            ]
        );

    $services->set(RoleVoter::class, RoleVoter::class);
    $services->set(RoleHierarchyVoter::class, RoleHierarchyVoter::class)
        ->args(
            [
                service(RoleHierarchy::class),
            ]
        );
    /*$services->set(RoleHierarchy::class, RoleHierarchy::class)
        ->args(
            [
                include __DIR__ . '/../../../../../../../resources/file-db/user/user_roles.php',
            ]
        );*/

    $services->set(AuthorizationChecker::class, AuthorizationChecker::class)
        ->args(
            [
                service(TokenStorageInterface::class),
                service(AccessDecisionManagerInterface::class),
            ]
        );

    $services->alias(AuthorizationCheckerInterface::class, AuthorizationChecker::class);
    $services->alias('security.authorization_checker', AuthorizationChecker::class);
};