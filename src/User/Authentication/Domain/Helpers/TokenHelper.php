<?php

namespace Untek\User\Authentication\Domain\Helpers;

use Untek\Core\Contract\Common\Exceptions\InvalidMethodParameterException;
use Untek\User\Authentication\Domain\Entities\TokenValueEntity;

class TokenHelper
{

    public static function parseToken(string $token): TokenValueEntity
    {
        $tokenSegments = explode(' ', $token);
        if (count($tokenSegments) != 2) {
            throw new InvalidMethodParameterException('Bad token format!');
        }
        list($tokenType, $tokenValue) = $tokenSegments;
        return new TokenValueEntity($tokenValue, mb_strtolower($tokenType));
    }
}
