<?php

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\NullTransport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Untek\Core\Env\Helpers\EnvHelper;

return [
    'singletons' => [
//        MailerInterface::class => Mailer::class,
//        TransportInterface::class => NullTransport::class,
        'Untek\Bundle\Notify\Domain\Interfaces\Repositories\EmailRepositoryInterface' => EnvHelper::isTest() ? 'Untek\Bundle\Notify\Domain\Repositories\File\EmailRepository' : 'Untek\Bundle\Notify\Domain\Repositories\Telegram\EmailRepository',
        'Untek\Bundle\Notify\Domain\Interfaces\Repositories\SmsRepositoryInterface' => EnvHelper::isTest() ? 'Untek\Bundle\Notify\Domain\Repositories\File\SmsRepository' : 'Untek\Bundle\Notify\Domain\Repositories\Telegram\SmsRepository',

        'Untek\Bundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface' => 'Untek\Bundle\Notify\Domain\Services\ToastrService',
        'Untek\Bundle\Notify\Domain\Interfaces\Services\FlashServiceInterface' => 'Untek\Bundle\Notify\Domain\Services\FlashService',
        'Untek\Bundle\Notify\Domain\Interfaces\Services\SmsServiceInterface' => 'Untek\Bundle\Notify\Domain\Services\SmsService',
        'Untek\Bundle\Notify\Domain\Interfaces\Services\EmailServiceInterface' => 'Untek\Bundle\Notify\Domain\Services\EmailService',
        'Untek\Bundle\Notify\Domain\Interfaces\Repositories\FlashRepositoryInterface' => 'Untek\Bundle\Notify\Domain\Repositories\Session\FlashRepository',
        //'Untek\Bundle\Notify\Domain\Interfaces\Repositories\ToastrRepositoryInterface' => 'Untek\Bundle\Notify\Domain\Repositories\Session\ToastrRepository',
    ],
];