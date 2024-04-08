<?php

namespace Untek\Framework\Telegram\Domain\Interfaces;

use Untek\Framework\Telegram\Domain\Entities\RequestEntity;

interface MatcherInterface
{

    public function isMatch(RequestEntity $requestEntity): bool;

}