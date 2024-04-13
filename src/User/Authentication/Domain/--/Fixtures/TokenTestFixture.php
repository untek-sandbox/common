<?php

namespace Untek\User\Authentication\Domain\Fixtures;

use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class TokenTestFixture
{

    public $baseToken = 'XAgQTzcEFIK2PrvtwkMyvDFD6bUjgq33z3kycw9-3t-RY2Ru1NpAc3q5fr5NeRG';

    public function __construct(string $baseToken = null)
    {
        if ($baseToken) {
            $this->baseToken = $baseToken;
        }
    }

    public function generate()
    {
        $collection = [];
        for ($i = 1; $i <= 10; $i++) {
            $collection[] = [
                'id' => $i,
                'identity_id' => $i,
                'type' => 'bearer',
                'value' => $this->generateToken($i),
                'created_at' => '2021-05-27 06:59:07',
                'expired_at' => null,
            ];
        }
        return $collection;
    }

    public function generateToken($i)
    {
        $baseToken = $baseToken ?? $this->baseToken;
        return $baseToken . $i;
    }
}
