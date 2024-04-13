<?php

namespace Untek\Component\Web\Form\Libs\Renders;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\ConstraintViolation;
use Untek\Component\Web\Form\Helpers\FormErrorHelper;

class HintRender extends BaseInputRender
{

    public function tagName(): string
    {
        return 'p';
    }

    public function defaultOptions(): array
    {
        return [
            'class' => "help-block help-block-error",
        ];
    }

    public function render(): string
    {
        $inputVars = $this->getViewOptions();
        $errors = FormErrorHelper::getErrorArray($this->getFormView());

        foreach ($errors as $error) {
            /** @var FormError $formError */
            $formError = $error['formError'];
            /** @var ConstraintViolation $cause */
            $cause = $formError->getCause();
            /** @var FormView $formError */
            $formView = $error['view'] ?? null;
            if ($cause && $formView) {
                if ($formView->vars['full_name'] == $inputVars['full_name']) {
                    $message = $cause->getMessage();
                    return '<div class="text-danger">
                          ' . $message . '
                        </div>';
                }
            }
        }
        return '';
    }
}
