<?php

namespace Untek\Persistence\Contract\Interfaces;

use Doctrine\Persistence\ObjectRepository;

/**
 * Сбор интерфейсов CRUD-операций в одном контракте для упрощения использования.
 */
interface RepositoryCrudInterface extends
    RepositoryCountByInterface,
    RepositoryCreateInterface,
    RepositoryDeleteByIdInterface,
    RepositoryFindOneByIdInterface,
    RepositoryUpdateInterface,
    ObjectRepository
{
}
