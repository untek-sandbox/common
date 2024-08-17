<?php

namespace Untek\Sandbox\Sandbox\EgovData\Domain\Libs;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Domain\Query\Entities\Where;
use Untek\Model\Query\Entities\Query;

class EgovDataProvider
{

    private $client;
    private $apiVersion = 'v4';
    private $datasetName;
    private $datasetVersion;

    public function __construct(EgovDataClient $client, string $datasetName, string $datasetVersion)
    {
        $this->client = $client;
        $this->datasetName = $datasetName;
        $this->datasetVersion = $datasetVersion;
    }

    public function findAll(Query $query): Enumerable
    {
        $params = $this->forgeParamsFromQuery($query);
        $data = $this->client->request('api/' . $this->apiVersion . '/' . $this->datasetName . '/' . $this->datasetVersion, $params);
        return new Collection($data);
    }

    protected function forgeParamsFromQuery(Query $query): array
    {
        $params = [];
        if ($query->getParam(Query::PAGE)) {
            $params['from'] = $query->getParam(Query::PAGE);
        }
        if ($query->getParam(Query::PER_PAGE)) {
            $params['size'] = $query->getParam(Query::PER_PAGE);
        }
        if ($query->getParam(Query::WHERE_NEW)) {
            /** @var Where[] $whereList */
            $whereList = $query->getParam(Query::WHERE_NEW);
            foreach ($whereList as $where) {
                $params['query']['bool']['must'][] = [
                    'match' => [$where->column => $where->value],
                ];
            }
        }
        return $params;
    }
}
