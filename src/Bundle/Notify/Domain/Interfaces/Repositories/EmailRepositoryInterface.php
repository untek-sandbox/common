<?php

namespace Untek\Bundle\Notify\Domain\Interfaces\Repositories;

use Untek\Bundle\Notify\Domain\Entities\EmailEntity;

interface EmailRepositoryInterface
{

    public function send(EmailEntity $emailEntity);

}