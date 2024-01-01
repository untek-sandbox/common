<?php

namespace Untek\Bundle\Notify\Domain\Enums;

use Untek\Core\Enum\Interfaces\GetLabelsInterface;

class NotifyPermissionEnum implements GetLabelsInterface
{

    const TEST_READ = 'oNotifyTestRead';

    public static function getLabels()
    {
        return [
            self::TEST_READ => 'Список тестовых сообщений. Чтение',
        ];
    }
}