<?php

namespace Untek\Bundle\Notify\Domain\Interfaces\Services;

use Untek\Bundle\Notify\Domain\Entities\SmsEntity;
use Untek\Bundle\Queue\Domain\Enums\PriorityEnum;

interface SmsServiceInterface
{

    public function push(SmsEntity $smsEntity, $priority = PriorityEnum::NORMAL);

}