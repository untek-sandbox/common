<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $commandFullClassName
 */

?>

namespace <?= $namespace ?>;

use Untek\Model\Cqrs\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers\AbstractRestApiController;
use <?= $commandFullClassName ?>;

class <?= $className ?> extends AbstractRestApiController
{

    public function __construct(private CommandBusInterface $bus, private UrlGeneratorInterface $urlGenerator)
    {
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
        return $this->serialize($result);
    }
}