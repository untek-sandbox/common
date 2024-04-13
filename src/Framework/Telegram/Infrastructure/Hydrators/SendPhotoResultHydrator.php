<?php

namespace Untek\Framework\Telegram\Infrastructure\Hydrators;

use Untek\Core\Instance\Helpers\MappingHelper;
use Untek\Database\Base\Hydrator\HydratorInterface;
use Untek\Framework\Telegram\Domain\Dto\Photo;
use Untek\Framework\Telegram\Domain\Dto\SendPhotoResult;

class SendPhotoResultHydrator implements HydratorInterface
{

    public function dehydrate(object $entity): array
    {

    }

    public function hydrate(array $item, object $entity = null): object
    {
        $photo = $item['photo'];
        unset($item['photo']);
        unset($item['caption_entities']);
        $photoObjects = [];
        foreach ($photo as $photoItem) {
            $photoObjects[] = MappingHelper::restoreObject($photoItem, Photo::class);
        }
        /** @var SendPhotoResult $dto */
        $dto = MappingHelper::restoreObject($item, SendPhotoResult::class);
        $dto->setPhoto($photoObjects);
        return $dto;
    }
}