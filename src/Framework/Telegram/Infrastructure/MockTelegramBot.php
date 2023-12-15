<?php

namespace Untek\Framework\Telegram\Infrastructure;

use RuntimeException;
use Untek\Core\Enum\Helpers\EnumHelper;
use Untek\Core\Instance\Helpers\MappingHelper;
use Untek\Framework\Telegram\Application\Services\TelegramBotInterface;
use Untek\Framework\Telegram\Domain\Dto\SendDocumentResult;
use Untek\Framework\Telegram\Domain\Dto\SendMessageResult;
use Untek\Framework\Telegram\Domain\Dto\SendPhotoResult;
use Untek\Framework\Telegram\Domain\Enums\ParseModeEnum;
use Untek\Framework\Telegram\Infrastructure\Hydrators\SendDocumentResultHydrator;
use Untek\Framework\Telegram\Infrastructure\Hydrators\SendPhotoResultHydrator;

class MockTelegramBot implements TelegramBotInterface
{

    public function __construct(private $botToken)
    {
    }

    /**
     * @param int $chatId
     * @param string $text
     * @param string $parseMode
     * @throws RuntimeException
     */
    public function sendMessage(int $chatId, string $text, string $parseMode = ''): SendMessageResult
    {
        EnumHelper::validate(ParseModeEnum::class, $parseMode);
        $response = [
            'message_id' => time(),
            'from' => [
                'id' => 5826959599,
                'is_bot' => true,
                'first_name' => 'Qwerty bot',
                'username' => 'qwerty_bot'
            ],
            'chat' => [
                'id' => $chatId,
                'first_name' => 'Qwerty',
                'username' => 'qwerty',
                'type' => 'private'
            ],
            'date' => time(),
            'text' => $text,
        ];
        return MappingHelper::restoreObject($response, SendMessageResult::class);
    }

    public function sendDocument(int $chatId, string $file, string $caption = null, string $parseMode = ''): SendDocumentResult
    {
        EnumHelper::validate(ParseModeEnum::class, $parseMode);
        $response = [
            'message_id' => time(),
            'from' => [
                'id' => 5826959599,
                'is_bot' => true,
                'first_name' => 'Qwerty bot',
                'username' => 'qwerty_bot'
            ],
            'chat' => [
                'id' => $chatId,
                'first_name' => 'Qwerty',
                'username' => 'qwerty',
                'type' => 'private'
            ],
            'date' => time(),
            "document" => [
                "file_name" => "log.zip",
                "mime_type" => "application\/zip",
                "thumbnail" => [
                    "file_id" => "AAMCAgADGQMAAg9qZXwiH2_8R7fas8PR-qcna1gyl7kAAhg6AAKvRuFLl9p90iIfEFIBAAdtAAMzBA",
                    "file_unique_id" => "AQADGDoAAq9G4Uty",
                    "file_size" => 14077,
                    "width" => 320,
                    "height" => 317,
                ],
                "thumb" => [
                    "file_id" => "AAMCAgADGQMAAg9qZXwiH2_8R7fas8PR-qcna1gyl7kAAhg6AAKvRuFLl9p90iIfEFIBAAdtAAMzBA",
                    "file_unique_id" => "AQADGDoAAq9G4Uty",
                    "file_size" => 14077,
                    "width" => 320,
                    "height" => 317,
                ],
                "file_id" => "BQACAgIAAxkDAAIPRGV7-qtMoCgdVg6GyIDFIRrEr-qaAALnOAACr0bhS7hk0VDd02HcMwQ",
                "file_unique_id" => "AgAD5zgAAq9G4Us",
                "file_size" => 18271
            ],
            "caption" => $caption
        ];
        return (new SendDocumentResultHydrator())->hydrate($response);
    }

    public function sendPhoto(int $chatId, string $file, string $caption = null, string $parseMode = ''): SendPhotoResult
    {
        EnumHelper::validate(ParseModeEnum::class, $parseMode);
        $response = [
            'message_id' => time(),
            'from' => [
                'id' => 5826959599,
                'is_bot' => true,
                'first_name' => 'Qwerty bot',
                'username' => 'qwerty_bot'
            ],
            'chat' => [
                'id' => $chatId,
                'first_name' => 'Qwerty',
                'username' => 'qwerty',
                'type' => 'private'
            ],
            'date' => time(),
            'photo' => [
                [
                    "file_id" => "AgACAgIAAxkDAAIPR2V8EuZ2S5H1EzOYooN_m3YkNFXTAAKG2jEbrKTAS81NnBep5TuRAQADAgADcwADMwQ",
                    "file_unique_id" => "AQADhtoxG6ykwEt4",
                    "file_size" => 1668,
                    "width" => 90,
                    "height" => 89
                ],
                [
                    "file_id" => "AgACAgIAAxkDAAIPR2V8EuZ2S5H1EzOYooN_m3YkNFXTAAKG2jEbrKTAS81NnBep5TuRAQADAgADbQADMwQ",
                    "file_unique_id" => "AQADhtoxG6ykwEty",
                    "file_size" => 15780,
                    "width" => 320,
                    "height" => 317
                ],
                [
                    "file_id" => "AgACAgIAAxkDAAIPR2V8EuZ2S5H1EzOYooN_m3YkNFXTAAKG2jEbrKTAS81NnBep5TuRAQADAgADeAADMwQ",
                    "file_unique_id" => "AQADhtoxG6ykwEt9",
                    "file_size" => 18575,
                    "width" => 394,
                    "height" => 390
                ]
            ],
            'caption' => $caption,
        ];
        return (new SendPhotoResultHydrator())->hydrate($response);
    }

    public function editMessage(int $chatId, int $messageId, string $text, string $parseMode = 'Markdown'): SendMessageResult
    {
        $response = [
            'message_id' => $messageId,
            'from' => [
                'id' => 5826959599,
                'is_bot' => true,
                'first_name' => 'Qwerty bot',
                'username' => 'qwerty_bot'
            ],
            'chat' => [
                'id' => $chatId,
                'first_name' => 'Qwerty',
                'username' => 'qwerty',
                'type' => 'private'
            ],
            'date' => time(),
            'edit_date' => time(),
            'text' => $text,
        ];
        return MappingHelper::restoreObject($response, SendMessageResult::class);
    }
}
