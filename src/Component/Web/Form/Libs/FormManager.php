<?php

namespace Untek\Component\Web\Form\Libs;

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
use Untek\Component\Web\Form\Interfaces\BuildFormInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Model\Validator\Exceptions\UnprocessibleEntityException;
use Untek\Model\Validator\Helpers\ValidationHelper;

class FormManager
{
    private $type = FormType::class;

    public function __construct(
        private FormFactoryInterface $formFactory,
        private CsrfTokenManagerInterface $tokenManager
    ) {
    }

    public function createFormRender(FormInterface $buildForm): FormRender
    {
        return new FormRender($buildForm->createView(), $this->tokenManager);
    }

    public function setErrorToForm(FormInterface $buildForm, string $field, string $message): void
    {
        $formError = $this->createFormError($field, $message);
        $buildForm->addError($formError);
    }

    public function buildForm(BuildFormInterface $form, Request $request): FormInterface
    {
        $formBuilder = $this->createFormBuilder($form);
        /*if ($form instanceof BuildFormInterface) {
            $form->buildForm($formBuilder);
        }*/
        return $this->formBuilderToForm($formBuilder, $request);
    }

    protected function createFormError(string $field, string $message)
    {
        $violation = new ConstraintViolation($message, null, [], 'Root', $field, null, null, null, null);
        return new FormError($message, null, [], null, $violation);
    }

    public function setUnprocessableErrorsToForm(FormInterface $buildForm, UnprocessableEntityException $e): void
    {
        foreach ($e->getViolations() as $errorEntity) {
            $this->setErrorToForm($buildForm, $errorEntity->getPropertyPath(), $errorEntity->getMessage());
        }
    }
    
    /*public function setUnprocessableErrorsToForm(FormInterface $buildForm, UnprocessibleEntityException $e): void
    {
        foreach ($e->getErrorCollection() as $errorEntity) {
            $formError = $this->createFormError($errorEntity->getField(), $errorEntity->getMessage());
//            $violation = new ConstraintViolation($errorEntity->getMessage(), null, [], 'Root', $errorEntity->getField(), null, null, null, null, $e);
//            $formError = new FormError($errorEntity->getMessage(), null, [], null, $violation);
            $buildForm->addError($formError);
        }
    }*/

    protected function formBuilderToForm(FormBuilderInterface $formBuilder, Request $request)
    {
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

    private function createFormBuilder(object $form, array $options = []): FormBuilderInterface
    {
        $formBuilder = $this->formFactory->createBuilder($this->type, $form, $options);
        if ($form instanceof BuildFormInterface) {
            $form->buildForm($formBuilder);
        }
        return $formBuilder;
    }
}
