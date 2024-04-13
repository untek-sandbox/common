<?php

namespace Untek\Component\Web\Form\Libs\Renders;

use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\ConstraintViolation;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Component\Web\Html\Helpers\Html;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Component\I18Next\Facades\I18Next;
use Untek\Component\Web\Form\Helpers\FormErrorHelper;

class FormErrorRender extends BaseRender
{

    public function render(): string
    {
        $errorMessages = [];
        //$html = '';
        $errors = FormErrorHelper::getErrorArray($this->getFormView());
        if($errors) {
            foreach ($errors as $error) {
                /** @var FormError $formError */
                $formError = $error['formError'];
                /** @var ConstraintViolation $cause */
                $cause = $formError->getCause();
                if ($cause) {
//                    $label = $error['view']->vars['label'];
                    $label = ArrayHelper::getValue($error, 'view.vars.label');
                    if($label) {
                        $message = $label . ': ' . $cause->getMessage();
                    } else {
                        $message = $cause->getMessage();
                    }
                } else {
                    $message = $formError->getMessage();
                }
                if(!empty($message)) {
                    $errorMessages[] = $message;
                    /*$html .= Html::tag('div', $message, [
                        'class' => 'alert alert-danger',
                        'role' => 'alert',
                    ]);*/
                }
            }
            if(empty($errorMessages)) {
                $errorMessages[] = 'Has errors!';
            }
        }

        if($errorMessages) {
            $errorMessageText = implode('<br/>', $errorMessages);
            $content =
                '<h5 class="alert-heading">'.I18Next::t('core', 'message.errors_found').'</h5>' .
                $errorMessageText;
            return Html::tag('div', $content, [
                'class' => 'alert alert-danger',
                'role' => 'alert',
            ]);
        } else {
            return '';
        }

//        foreach ($this->getFormView()->vars['errors'] as $error) {
//            /** @var FormError $error */
//            if ($error->getCause()) {
//                $errorAttribute = $error->getCause()->getPropertyPath();
//                $message = $errorAttribute . ': ' . $error->getMessage();
//                dd($this->getFormView());
//            } else {
//                $errorAttribute = null;
//                $message = $error->getMessage();
//            }
//            $html .= Html::tag('div', $message, [
//                'class' => 'alert alert-danger',
//                'role' => 'alert',
//            ]);
//            //$html .= ' <div class="alert alert-danger" role="alert">'.  .'</div> ';
//        }
        return $html;
    }
}
