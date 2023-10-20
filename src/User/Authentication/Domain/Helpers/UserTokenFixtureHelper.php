<?php

namespace Untek\User\Authentication\Domain\Helpers;

use Untek\Core\Env\Helpers\EnvHelper;

class UserTokenFixtureHelper
{

    public static function generate(array $identityCollection): array
    {
        $collection = [];
        if(!EnvHelper::isProd()) {
            $baseToken = 'XAgQTzcEFIK2PrvtwkMyvDFD6bUjgq33z3kycw9-3t-RY2Ru1NpAc3q5fr5NeRG';
            foreach ($identityCollection as $i => $identity) {
                $collection[] = [
                    'identity_id' => $identity['id'],
                    'type' => 'bearer',
                    'value' => $baseToken . $identity['id'],
                    'created_at' => '2021-05-27 06:59:07',
                    'expired_at' => null,
                ];
            }
        }
        return $collection;
    }
}
