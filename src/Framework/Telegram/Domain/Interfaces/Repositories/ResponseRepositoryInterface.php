<?php


namespace Untek\Framework\Telegram\Domain\Interfaces\Repositories;

use Untek\Framework\Telegram\Domain\Entities\BotEntity;
use Untek\Framework\Telegram\Domain\Entities\ResponseEntity;

interface ResponseRepositoryInterface
{

    public function send(ResponseEntity $responseEntity, BotEntity $botEntity);

}