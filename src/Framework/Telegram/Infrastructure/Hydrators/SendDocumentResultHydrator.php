<?php

namespace Untek\Framework\Telegram\Infrastructure\Hydrators;

use Untek\Core\Instance\Helpers\MappingHelper;
use Untek\Database\Base\Hydrator\HydratorInterface;
use Untek\Framework\Telegram\Domain\Dto\SendDocumentResult;

class SendDocumentResultHydrator implements HydratorInterface
{

    public function dehydrate(object $entity): array
    {

    }

    public function hydrate(array $item, object $entity = null): object
    {
        unset($item['caption_entities']);
        $dto = MappingHelper::restoreObject($item, SendDocumentResult::class);
        return $dto;
    }
}