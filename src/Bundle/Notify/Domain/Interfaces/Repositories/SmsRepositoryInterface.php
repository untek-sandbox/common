<?php

namespace Untek\Bundle\Notify\Domain\Interfaces\Repositories;

use Untek\Bundle\Notify\Domain\Entities\SmsEntity;

interface SmsRepositoryInterface
{

    public function send(SmsEntity $smsEntity);

}