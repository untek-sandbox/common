<?php

/**
 * @var string $namespace
 * @var string $className
 */

?>

namespace <?= $namespace ?>;

use Untek\Model\Contract\Interfaces\RepositoryCreateInterface;
use Untek\Model\Contract\Interfaces\RepositoryDeleteByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryFindOneByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryUpdateInterface;
use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;

interface <?= $className ?> extends
    RepositoryCountByInterface,
    RepositoryCreateInterface,
    RepositoryDeleteByIdInterface,
    RepositoryFindOneByIdInterface,
    RepositoryUpdateInterface
{

}