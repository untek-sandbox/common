<?php

namespace Untek\Framework\Telegram\Domain\Matchers;

use Untek\Framework\Telegram\Domain\Entities\RequestEntity;
use Untek\Framework\Telegram\Domain\Interfaces\MatcherInterface;

class AnyMatcher implements MatcherInterface
{

    public function isMatch(RequestEntity $requestEntity): bool
    {
        if($requestEntity->getMessage()->getText() == '') {
            return false;
        }
        return true;
    }

}