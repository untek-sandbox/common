<?php

namespace Untek\Component\Web\Error\Symfony4\Controllers;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Contract\Common\Exceptions\InvalidConfigException;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Core\Env\Helpers\EnvHelper;
use Untek\Component\Web\Controller\Base\BaseWebController;
use Untek\Component\Web\Error\Symfony4\Interfaces\ErrorControllerInterface;
use Untek\User\Authentication\Symfony4\Web\Enums\WebUserEnum;

DeprecateHelper::hardThrow();

class ErrorController extends BaseWebController implements ErrorControllerInterface
{

    protected $viewsDir = __DIR__ . '/../views/error';
    private $session;
    private $logger;
    private $urlGenerator;

    public function __construct(SessionInterface $session, LoggerInterface $logger, UrlGeneratorInterface $urlGenerator)
    {
        $this->session = $session;
        $this->logger = $logger;
        $this->urlGenerator = $urlGenerator;
    }

    public function handleError(Request $request, \Exception $exception): Response
    {
        $data = [
            'attributes' => $request->attributes->all(),
            'request' => $request->request->all(),
            'query' => $request->query->all(),
            'server' => $request->server->all(),
            'files' => $request->files->all(),
            'cookies' => $request->cookies->all(),
            'headers' => $request->headers->all(),
            'requestUri' => $request->getRequestUri(),
            'method' => $request->getMethod(),
        ];
        $logMessage = $exception->getMessage() ?: get_class($exception);
        $this->logger->error($logMessage, [
            'request' => $data,
            'trace' => debug_backtrace()
        ]);
        if ($exception instanceof AccessDeniedException) {
            return $this->forbidden($request, $exception);
        }
        if ($exception instanceof AuthenticationException) {
            return $this->unauthorized($request, $exception);
        }
        if ($exception instanceof NotFoundException) {
            return $this->notFound($request, $exception);
        }
        if ($exception instanceof ResourceNotFoundException) {
            return $this->notFound($request, $exception);
        }
        if ($exception instanceof InvalidConfigException) {
            return $this->commonRender('Config error', $exception->getMessage(), $exception);
        }
        return $this->commonRender('Error!', $exception->getMessage(), $exception);
    }

    private function commonRender(string $title, string $message, \Throwable $exception): Response
    {
        $params = [
            'title' => $title,
            'message' => $message,
        ];
        if (EnvHelper::isDebug()) {
            $params['exception'] = $exception;
        }
        return $this->render('handle-error', $params);
    }

    private function notFound(Request $request, \Exception $exception): Response
    {
        return $this->commonRender('Not found', 'Page not exists!', $exception);
    }

    private function unauthorized(Request $request, \Exception $exception): Response
    {
        $authUrl = $this->urlGenerator->generate('user/auth');
        if ($request->getRequestUri() == $authUrl) {
            return $this->commonRender('Unauthorized', 'Unauthorized!', $exception);
        }
        $this->session->set(WebUserEnum::UNAUTHORIZED_URL_SESSION_KEY, $request->getRequestUri());
        return $this->redirect($authUrl);
    }

    private function forbidden(Request $request, \Exception $exception): Response
    {
        return $this->commonRender('Forbidden', 'Access error', $exception);
    }
}
