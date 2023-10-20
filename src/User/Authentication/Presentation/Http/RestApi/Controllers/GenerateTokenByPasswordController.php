<?php

namespace Untek\User\Authentication\Presentation\Http\RestApi\Controllers;

use Untek\Model\Cqrs\CommandBusInterface;
use Untek\User\Authentication\Application\Commands\GenerateTokenByPasswordCommand;
use Untek\User\Authentication\Domain\Exceptions\BadPasswordException;
use Untek\User\Authentication\Domain\Exceptions\LoginNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Controllers\AbstractRestApiController;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class GenerateTokenByPasswordController extends AbstractRestApiController
{

    public function __construct(private CommandBusInterface $bus)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var GenerateTokenByPasswordCommand $command */
        $command = $this->createForm($request, GenerateTokenByPasswordCommand::class);

        try {
            $tokenDto = $this->bus->handle($command);
        } catch (BadPasswordException $e) {
            throw UnprocessableEntityException::create($e->getMessage(), null, [], null, '[password]', null);
        } catch (LoginNotFoundException $e) {
            throw UnprocessableEntityException::create($e->getMessage(), null, [], $command, '[login]', $command->getLogin());
        }

        return new JsonResponse([
            'token' => $tokenDto->getToken()
        ]);
    }
}
