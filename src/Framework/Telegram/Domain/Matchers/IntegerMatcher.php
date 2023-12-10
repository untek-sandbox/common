<?php

namespace Untek\Framework\Telegram\Domain\Matchers;

use Untek\Framework\Telegram\Domain\Helpers\MatchHelper;
use Untek\Framework\Telegram\Domain\Interfaces\MatcherInterface;

class IntegerMatcher implements MatcherInterface
{

    private $patterns;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    public function isMatch(array $update): bool
    {
        $message = $update['message']['message'];
        return is_int($message);
    }

}