<?php

use Untek\Core\EventDispatcher\Interfaces\EventDispatcherConfiguratorInterface;

return function (EventDispatcherConfiguratorInterface $configurator): void {
    $configurator->addSubscriber(\Untek\User\Authentication\Domain\Subscribers\SymfonyAuthenticationIdentitySubscriber::class);
    /*$configurator->addSubscriber(
        [
            'class' => \Untek\User\Authentication\Domain\Subscribers\AuthenticationAttemptSubscriber::class,
            'action' => 'authorization',
            // todo: вынести в настройки
            'attemptCount' => 3,
            'lifeTime' => 10,
//                'lifeTime' => TimeEnum::SECOND_PER_MINUTE * 30,
        ]
    );*/
};
