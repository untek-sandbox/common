<?php

namespace Untek\Bundle\Notify\Domain\Interfaces\Services;

use Untek\Bundle\Notify\Domain\Entities\EmailEntity;
use Untek\Bundle\Queue\Domain\Enums\PriorityEnum;

interface EmailServiceInterface
{

    public function push(EmailEntity $emailEntity, $priority = PriorityEnum::NORMAL);

}