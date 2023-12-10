<?php

namespace Untek\Framework\Telegram\Domain\Facades;

class Bot
{

    private static $responseService = null;
    private static $chatId = null;

    public static function init(string $token, int $chatId = null)
    {
        self::$chatId = $chatId;
        self::$responseService = BotFacade::getResponseService($token);
    }

    public static function dump($message, int $chatId = null, bool $isEncode = true)
    {
        $messageText = self::encodeMessage($message, $isEncode);
        self::sendMessage($messageText, $chatId);
    }

    public static function send($message, int $chatId = null, bool $isEncode = true)
    {
        self::dump($message, $chatId, $isEncode);
    }

    public static function sendMessage(string $messageText, int $chatId = null, $envPrefix = 'DUMPER_BOT')
    {
        $chatId = $chatId ?: self::$chatId;
        $chatId = $chatId ?: getenv($envPrefix . '_ADMIN_ID');
        if (empty(self::$responseService)) {
            self::$responseService = BotFacade::getResponseService(getenv($envPrefix . '_TOKEN'));
        }
        self::$responseService->sendMessage($chatId, $messageText);
    }

    public static function encodeMessage($messageData, bool $isEncode = true) {
        if ($isEncode) {
            $messageData = json_encode($messageData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        return $messageData;
    }
}
