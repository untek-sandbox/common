<?php

namespace Untek\Model\DataProvider\Interfaces;

use Forecast\Map\Modules\Driver\Application\Queries\GetDriverOrderHistoryListQuery;

interface DataProviderWithQueryInterface
{

    public function countByQuery(object $query): int;

    public function findByQuery(object $query): array;
}