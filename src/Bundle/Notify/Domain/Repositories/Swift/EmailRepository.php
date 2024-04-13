<?php

namespace Untek\Bundle\Notify\Domain\Repositories\Swift;

use Untek\Bundle\Notify\Domain\Entities\EmailEntity;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\EmailRepositoryInterface;

class EmailRepository implements EmailRepositoryInterface
{

    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(EmailEntity $emailEntity)
    {
        $message = new \Swift_Message;
        $message->setFrom($emailEntity->getFrom());
        $message->setTo($emailEntity->getTo());
        $message->setSubject($emailEntity->getSubject());
        $message->setBody($emailEntity->getBody());
        $result = $this->mailer->send($message);
        $isOk = $result > 0;
        if ( ! $isOk) {
            throw new \Exception('Email send error!');
        }
        return $isOk;
    }

}
