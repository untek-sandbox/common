<?php

namespace Untek\Component\LogReader\Infrastructure\Persistence\Normalizer;

use DateTime;
use Untek\Database\Base\Hydrator\DatabaseItemNormalizer;

class LogItemNormalizer extends DatabaseItemNormalizer
{

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        $data['created_at'] = new DateTime($data['datetime'] ?? null);
        return parent::denormalize($data, $type, $format, $context);
    }
}