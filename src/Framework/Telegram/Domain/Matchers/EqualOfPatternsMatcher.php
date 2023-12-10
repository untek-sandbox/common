<?php

namespace Untek\Framework\Telegram\Domain\Matchers;

use Untek\Framework\Telegram\Domain\Entities\RequestEntity;
use Untek\Framework\Telegram\Domain\Helpers\MatchHelper;
use Untek\Framework\Telegram\Domain\Interfaces\MatcherInterface;

class EqualOfPatternsMatcher implements MatcherInterface
{

    private $patterns;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    public function isMatch(RequestEntity $requestEntity): bool
    {
        $message = $requestEntity->getMessage()->getText();
        foreach ($this->patterns as $pattern) {
            if(MatchHelper::isMatchText($message, $pattern)) {
                return true;
            }
        }
        return false;
    }

}