<?php

namespace Untek\Crypt\Pki\X509\Domain;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Domain\Domain\Interfaces\DomainInterface;

DeprecateHelper::hardThrow();

class Domain implements DomainInterface
{

    public function getName()
    {
        return 'apache';
    }

}
