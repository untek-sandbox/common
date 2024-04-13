<?php

namespace Untek\Bundle\Notify\Domain\Repositories\Telegram;

use Untek\Bundle\Notify\Domain\Entities\EmailEntity;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\EmailRepositoryInterface;
use Untek\Framework\Telegram\Domain\Facades\Bot;

class EmailRepository implements EmailRepositoryInterface
{

    public function send(EmailEntity $emailEntity)
    {
        $message =
            '# Email' . PHP_EOL .
            'From: ' . $emailEntity->getFrom() . PHP_EOL .
            'To: ' . $emailEntity->getTo() . PHP_EOL .
            'Subject: ' . $emailEntity->getSubject() . PHP_EOL .
            'Body: ' . $emailEntity->getBody();
        Bot::sendMessage($message);
    }
}
