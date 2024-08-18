<?php

use Psr\Container\ContainerInterface;
//use Untek\Lib\Components\ShellRobot\Domain\Libs\App\ConfigProcessor;
//use Untek\Lib\Components\ShellRobot\Domain\Libs\App\ConnectionProcessor;
//use Untek\Lib\Components\ShellRobot\Domain\Libs\App\VarProcessor;

return [
    'singletons' => [
        \Untek\Lib\Components\ShellRobot\Domain\Interfaces\Repositories\VarRepositoryInterface::class => function (ContainerInterface $container) {
            /** @var \Untek\Core\App\Interfaces\EnvStorageInterface $envStorage */
            $envStorage = $container->get(\Untek\Core\App\Interfaces\EnvStorageInterface::class);

            $config = include($envStorage->get('DEPLOYER_CONFIG_FILE'));
            $vars = $config['vars'];

            $user = $config['connections']['default']['user'];

            $vars['userName'] = $user;
            $vars['homeUserDir'] = "/home/{$user}";

            /*$vars['homeUserDir'] = function () use ($user) {
                return "/home/{$user}";
            };*/

            $varProcessor = new \Untek\Lib\Components\ShellRobot\Domain\Repositories\File\VarRepository($vars);
//            $varProcessor->setVars($vars);
            return $varProcessor;
        },
        \Untek\Lib\Components\ShellRobot\Domain\Interfaces\Repositories\ConfigRepositoryInterface::class => function (ContainerInterface $container) {
            /** @var \Untek\Core\App\Interfaces\EnvStorageInterface $envStorage */
            $envStorage = $container->get(\Untek\Core\App\Interfaces\EnvStorageInterface::class);

            $config = include($envStorage->get('DEPLOYER_CONFIG_FILE'));
            $configProcessor = new \Untek\Lib\Components\ShellRobot\Domain\Repositories\File\ConfigRepository($config);
//            $configProcessor->setConfig($config);
            return $configProcessor;
        },
        \Untek\Lib\Components\ShellRobot\Domain\Interfaces\Repositories\ConnectionRepositoryInterface::class => function (ContainerInterface $container) {
            $configProcessor = new \Untek\Lib\Components\ShellRobot\Domain\Repositories\File\ConnectionRepository();
            return $configProcessor;
        },



//        VarProcessor::class => function (ContainerInterface $container) {
///             ** @var \Untek\Core\App\Interfaces\EnvStorageInterface $envStorage */
//            $envStorage = $container->get(\Untek\Core\App\Interfaces\EnvStorageInterface::class);

//            $config = include($envStorage->get('DEPLOYER_CONFIG_FILE'));
//            $vars = $config['vars'];
//
//            $user = $config['connections']['default']['user'];
//
//            $vars['userName'] = $user;
//            $vars['homeUserDir'] = "/home/{$user}";
//
//            /*$vars['homeUserDir'] = function () use ($user) {
//                return "/home/{$user}";
//            };*/
//
//            $varProcessor = new VarProcessor($vars);
////            $varProcessor->setVars($vars);
//            return $varProcessor;
//        },
//        ConfigProcessor::class => function (ContainerInterface $container) {
//            /** @var \Untek\Core\App\Interfaces\EnvStorageInterface $envStorage */
//            $envStorage = $container->get(\Untek\Core\App\Interfaces\EnvStorageInterface::class);

//            $config = include($envStorage->get('DEPLOYER_CONFIG_FILE'));
//
//            $configProcessor = new ConfigProcessor($config);
////            $configProcessor->setConfig($config);
//            return $configProcessor;
//        },
//        ConnectionProcessor::class => function (ContainerInterface $container) {
//            $configProcessor = new ConnectionProcessor();
//            return $configProcessor;
//        },
    ],
];
