<?php

namespace Untek\Component\Web\Form\Traits;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Model\Validator\Entities\ValidationErrorEntity;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Model\Validator\Exceptions\UnprocessibleEntityException;
use Untek\Model\Validator\Helpers\ValidationHelper;
use Untek\Component\Web\Form\Interfaces\BuildFormInterface;
use Untek\Component\Web\Form\Libs\FormManager;
use Untek\Component\Web\Form\Libs\FormRender;

trait ControllerFormTrait
{

    private $tokenManager;
    private $formFactory;
    protected $formManager;
    private $type = FormType::class;

    // Внедрите зависимости в контроллер
    /*public function __construct(
        FormFactoryInterface $formFactory,
        CsrfTokenManagerInterface $tokenManager
    )
    {
        $this->setFormFactory($formFactory);
        $this->setTokenManager($tokenManager);
    }*/

    protected function getTokenManager(): CsrfTokenManagerInterface
    {
        return $this->tokenManager ?? $this->getFormManager()->getTokenManager();
    }

    protected function setTokenManager(CsrfTokenManagerInterface $tokenManager): void
    {
        $this->tokenManager = $tokenManager;
    }

    public function getFormFactory(): FormFactoryInterface
    {
        return $this->formFactory ?? $this->getFormManager()->getFormFactory();
    }

    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    public function getFormManager(): FormManager
    {
        return $this->formManager;
    }

    public function setFormManager(FormManager $formManager): void
    {
        $this->formManager = $formManager;
    }

    protected function getType(): string
    {
        return $this->type;
    }

    protected function setType(string $type): void
    {
        $this->type = $type;
    }

    public function createFormRender(FormInterface $buildForm): FormRender
    {
        DeprecateHelper::hardThrow();
        return new FormRender($buildForm->createView(), $this->getTokenManager());
    }

    public function buildForm(object $form, Request $request): FormInterface
    {
        /** @var BuildFormInterface $form */
        $formBuilder = $this->createFormBuilder($form);
        /*if ($form instanceof BuildFormInterface) {
            $form->buildForm($formBuilder);
        }*/
        return $this->formBuilderToForm($formBuilder, $request);
    }

    public function setUnprocessableErrorsToForm(FormInterface $buildForm, UnprocessableEntityException $e): void
    {
        foreach ($e->getViolations() as $errorEntity) {
            $this->setErrorToForm($buildForm, $errorEntity->getPropertyPath(), $errorEntity->getMessage());
        }
    }

    protected function setErrorToForm(FormInterface $buildForm, string $field, string $message): void
    {
        $formError = $this->createFormError($field, $message);
        $buildForm->addError($formError);
    }

    protected function createFormError(string $field, string $message) {
        /** @var ValidationErrorEntity $cause */
        //$cause = $errorEntity->getViolation();
        //$violation = new ConstraintViolation();
        $violation = new ConstraintViolation($message, null, [], 'Root', $field, null, null, null, null);
        //$cause->setViolation($violation);
        //dd($cause->getViolation());
//          $cause = new ConstraintViolation('Error 1!', null, [], null, '', null, null, 'code1');
        $formError = new FormError($message, null, [], null, $violation);
        return $formError;
    }

    protected function formBuilderToForm(FormBuilderInterface $formBuilder, Request $request) {
        $buildForm = $formBuilder->getForm();
        $buildForm->handleRequest($request);
        if ($buildForm->isSubmitted()) {
            if (isset($this->tokenManager)) {
                $this->validCsrfToken($this->tokenManager, $request);
            }
            $this->validate($buildForm);
            /*if ($buildForm->isValid()) {

            }*/
        }
        return $buildForm;
    }

    private function validate(FormInterface $buildForm)
    {
        try {
            ValidationHelper::validateEntity($buildForm->getData());
        } catch (UnprocessibleEntityException $e) {
            $this->setUnprocessableErrorsToForm($buildForm, $e);
        }
    }

    protected static function validCsrfToken(CsrfTokenManagerInterface $tokenManager, Request $request)
    {
        $csrfToken = new CsrfToken(getenv('CSRF_TOKEN_ID'), $request->get('csrfToken'));
        $isValidToken = $tokenManager->isTokenValid($csrfToken);
        if (!$isValidToken) {
            throw new BadRequestException('CSRF token validate error!');
        }
    }

    protected function createFormBuilder(object $form, array $options = []): FormBuilderInterface
    {
        $formBuilder = $this->getFormFactory()->createBuilder($this->type, $form, $options);
        if ($form instanceof BuildFormInterface) {
            $form->buildForm($formBuilder);
        }
        return $formBuilder;
    }
}
