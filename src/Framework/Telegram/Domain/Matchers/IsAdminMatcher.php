<?php

namespace Untek\Framework\Telegram\Domain\Matchers;

use Untek\Framework\Telegram\Domain\Entities\RequestEntity;
use Untek\Framework\Telegram\Domain\Helpers\MatchHelper;
use Untek\Framework\Telegram\Domain\Interfaces\MatcherInterface;

class IsAdminMatcher implements MatcherInterface
{

    public function isMatch(RequestEntity $requestEntity): bool
    {
        $message = $requestEntity->getMessage()->getText();
        $fromId = $requestEntity->getMessage()->getFrom()->getId();
        $toId = $requestEntity->getMessage()->getChat()->getId();
        
		if(empty($fromId) || empty($toId)) {
			return false;
		}
        $isSelf = $fromId == $toId;
        $isAdmin = $fromId == getenv('ADMIN_ID');
        return $isSelf || $isAdmin;
    }

}