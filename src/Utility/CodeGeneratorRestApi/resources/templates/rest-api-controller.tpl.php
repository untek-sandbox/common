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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers\AbstractRestApiController;
use <?= $commandFullClassName ?>;
use <?= $schemaClassName ?>;

class <?= $className ?> extends AbstractRestApiController
{

    public function __construct(private CommandBusInterface $bus, private UrlGeneratorInterface $urlGenerator)
    {
        $this->schema = new <?= \Untek\Core\Instance\Helpers\ClassHelper::getClassOfClassName($schemaClassName) ?>();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        /** @var <?= $commandClassName ?> $command */
        $command = $this->createForm($request, <?= $commandClassName ?>::class);
        $result =$this->bus->handle($command);
        $data = $this->encodeObject($result);
        return $this->serialize($data);
    }
}