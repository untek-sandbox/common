<?php

namespace Untek\Bundle\Notify\Domain\Repositories\Telegram;

use Untek\Bundle\Notify\Domain\Entities\SmsEntity;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\SmsRepositoryInterface;
use Untek\Framework\Telegram\Domain\Facades\Bot;

class SmsRepository implements SmsRepositoryInterface
{

    public function send(SmsEntity $smsEntity)
    {
        $message =
            '# SMS' . PHP_EOL .
            'Phone: ' . $smsEntity->getPhone() . PHP_EOL .
            'Message: ' . $smsEntity->getMessage();
        Bot::sendMessage($message);
    }
}
