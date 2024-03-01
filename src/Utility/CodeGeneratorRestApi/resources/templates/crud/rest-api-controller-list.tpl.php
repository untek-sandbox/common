<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $commandFullClassName
 */

?>

namespace <?= $namespace ?>;

use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Forecast\Map\Shared\Infrastructure\Http\RestApi\QueryParameterHelper;
use Forecast\Map\Shared\Infrastructure\Http\RestApi\Controllers\AbstractGetListRestApiController;
use <?= $commandFullClassName ?>;
use <?= $schemaClassName ?>;

class <?= $className ?> extends AbstractGetListRestApiController
{

    public function __construct(
        private CommandBusInterface $bus,
    )
    {
        $this->schema = new <?= \Untek\Core\Instance\Helpers\ClassHelper::getClassOfClassName($schemaClassName) ?>();
    }

    public function __invoke(Request $request): JsonResponse
    {
        $query = new <?= $commandClassName ?>();
        QueryParameterHelper::fillQuery($request->query->all(), $query);
        $collectionData = $this->bus->handle($query);
        return $this->createResponse($collectionData);
    }
}
