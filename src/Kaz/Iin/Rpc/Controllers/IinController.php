<?php

namespace Untek\Kaz\Iin\Rpc\Controllers;

use Untek\Domain\Entity\Helpers\EntityHelper;
use Untek\Kaz\Iin\Domain\Helpers\IinParser;
use Untek\Framework\Rpc\Domain\Entities\RpcRequestEntity;
use Untek\Framework\Rpc\Domain\Entities\RpcResponseEntity;

class IinController
{

    public function getInfo(RpcRequestEntity $requestEntity): RpcResponseEntity
    {
        $code = $requestEntity->getParamItem('code');
        $entity = IinParser::parse($code);
        $response = new RpcResponseEntity();
        $response->setResult(EntityHelper::toArray($entity, true));
        return $response;
    }
}
