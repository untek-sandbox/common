<?php

namespace Untek\User\AuthenticationWeb\Presentation\Http\Site\Controllers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Untek\Component\Http\Enums\HttpStatusCodeEnum;
use Untek\Component\Web\Controller\Abstract\AbstractWebController;
use Untek\Component\Web\Form\Libs\FormManager;
use Untek\Component\Web\View\Libs\View;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrServiceInterface;
use Untek\FrameworkPlugin\HttpAuthentication\Application\Services\WebAuthentication;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;

class UserLogoutController extends AbstractWebController
{

    public function __construct(
        View $view,
        FormManager $formManager,
        private WebAuthentication $webAuthentication,
        private CommandBusInterface $bus,
        private IdentityRepositoryInterface $identityRepository,
        ToastrServiceInterface $toastrService,
        private TokenStorageInterface $tokenStorage,
    )
    {
        $this->view = $view;
        $this->formManager = $formManager;
        $this->toastrService = $toastrService;
    }

    public function __invoke(Request $request): Response
    {
        $identityEntity = $this->tokenStorage->getToken()->getUser();
        if (!$identityEntity) {
            $this->toastrService->warning('Вы не авторизованы');
            return $this->redirectToHome();
        }

        $response = new RedirectResponse('/', HttpStatusCodeEnum::MOVED_TEMPORARILY);
        $this->webAuthentication->logout($response);
        $this->toastrService->success('Logout success');
        return $response;
    }
}
