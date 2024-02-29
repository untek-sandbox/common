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
use Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers\AbstractRestApiController;
use Untek\Component\App\Services\ControllerAccessChecker;
use <?= $commandFullClassName ?>;

class <?= $className ?> extends AbstractRestApiController
{

    public function __construct(
        private CommandBusInterface $bus,
        private ControllerAccessChecker $accessChecker,
    )
    {
    }

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $this->accessChecker->denyAccessUnlessAuthenticated();
        $command = new <?= $commandClassName ?>();
        $command->setId($id);
        $this->accessChecker->denyAccessUnlessGranted('ROLE_USER', $command);
        $this->bus->handle($command);
        return $this->emptyResponse();
    }
}