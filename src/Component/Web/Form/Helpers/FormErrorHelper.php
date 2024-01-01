<?php

namespace Untek\Component\Web\Form\Helpers;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;

class FormErrorHelper
{

    public static function getErrorArray(FormView $formView): array
    {
        $errors = [];
        foreach ($formView->vars['errors'] as $formError) {
            /** @var FormError $formError */
            $errors[] = [
                'name' => $formError->getCause() ? $formError->getCause()->getPropertyPath() : null,
                'formError' => $formError,
            ];
        }
        foreach ($formView->children as $childName => $childView) {
            foreach ($errors as &$formError) {
                if ($formError['name'] == $childView->vars['name']) {
                    $formError['view'] = $childView;
                }
            }
        }
        return $errors;
    }
}
