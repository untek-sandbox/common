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
use Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers\AbstractRestApiController;
use <?= $commandFullClassName ?>;
use <?= $schemaClassName ?>;

class <?= $className ?> extends AbstractRestApiController
{

    public function __construct(
        private CommandBusInterface $bus,
    )
    {
        $this->schema = new <?= \Untek\Core\Instance\Helpers\ClassHelper::getClassOfClassName($schemaClassName) ?>();
    }

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $query = new <?= $commandClassName ?>();
        $query->setId($id);
        QueryParameterHelper::fillQuery($request->query->all(), $query);

        $result = $this->bus->handle($query);
        $data = $this->encodeObject($result);
        return $this->serialize($data);
    }
}