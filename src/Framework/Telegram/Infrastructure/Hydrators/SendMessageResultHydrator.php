<?php

namespace Untek\Framework\Telegram\Infrastructure\Hydrators;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Instance\Helpers\MappingHelper;
use Untek\Database\Base\Hydrator\HydratorInterface;
use Untek\Framework\Telegram\Domain\Dto\SendMessageResult;

class SendMessageResultHydrator implements HydratorInterface
{

    public function dehydrate(object $entity): array
    {

    }

    public function hydrate(array $item, object $entity = null): object
    {
        $item = ArrayHelper::extractByKeys($item, [
            'message_id',
            'text',
            'date',
            'edit_date',
            'from',
            'chat',
        ]);
        /** @var SendMessageResult $dto */
        $dto = MappingHelper::restoreObject($item, SendMessageResult::class);
        return $dto;
    }
}