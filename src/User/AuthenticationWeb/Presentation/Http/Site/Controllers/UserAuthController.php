<?php

namespace Untek\User\AuthenticationWeb\Presentation\Http\Site\Controllers;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Untek\User\AuthenticationWeb\Presentation\Http\Site\Forms\AuthForm;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Untek\Component\Http\Enums\HttpStatusCodeEnum;
use Untek\Component\Web\Controller\Abstract\AbstractWebController;
use Untek\Component\Web\View\Libs\View;
use Untek\Component\Web\Form\Libs\FormManager;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrServiceInterface;
use Untek\FrameworkPlugin\HttpAuthentication\Application\Services\WebAuthentication;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Model\Validator\Exceptions\UnprocessibleEntityException;
use Untek\User\Authentication\Application\Commands\GenerateTokenByPasswordCommand;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Untek\User\Authentication\Domain\Model\Token;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserAuthController extends AbstractWebController
{

    protected string $templateFile = __DIR__ . '/../../../../resources/templates/user-auth.php';

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
        if ($identityEntity) {
            $this->toastrService->success('Вы уже авторизованы!');
            return $this->redirectToHome();
        }

        $form = new AuthForm();
        $buildForm = $this->formManager->buildForm($form, $request);
        if ($buildForm->isSubmitted() && $buildForm->isValid()) {
            $command = new GenerateTokenByPasswordCommand();
            $command->setLogin($form->getLogin());
            $command->setPassword($form->getPassword());

            try {
                /** @var Token $tokenDto */
                $tokenDto = $this->bus->handle($command);
                $response = new RedirectResponse('/', HttpStatusCodeEnum::MOVED_TEMPORARILY);
                $user = $this->identityRepository->getUserById($tokenDto->getIdentityId());
                $this->webAuthentication->login($response, $user, $form->getRememberMe());
                $this->toastrService->success('Auth success');
                return $response;

            } catch (\Untek\User\Authentication\Domain\Exceptions\BadPasswordException $e) {
                $this->setErrorToForm($buildForm, 'password', $e->getMessage());
//                $buildForm->addError(new FormError($e->getMessage()));
            } catch (UserNotFoundException $e) {
                $this->setErrorToForm($buildForm, 'login', $e->getMessage());
            } /*catch (UnprocessableEntityException $e) {
                dd(66);
            } catch (UnprocessibleEntityException $e) {
                dd(44);
                $this->setUnprocessableErrorsToForm($buildForm, $e);
            }*/
        }
        return $this->render([
            'formRender' => $this->formManager->createFormRender($buildForm),
        ]);
    }
}
