<?php

namespace Untek\Component\Web\Form\Libs;

use Symfony\Component\Form\FormView;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Text\Helpers\TemplateHelper;
use Untek\Component\Web\Form\Libs\Renders\BaseRender;
use Untek\Component\Web\Form\Libs\Renders\ButtonRender;
use Untek\Component\Web\Form\Libs\Renders\CheckboxRender;
use Untek\Component\Web\Form\Libs\Renders\FileRender;
use Untek\Component\Web\Form\Libs\Renders\FormErrorRender;
use Untek\Component\Web\Form\Libs\Renders\HiddenRender;
use Untek\Component\Web\Form\Libs\Renders\HintRender;
use Untek\Component\Web\Form\Libs\Renders\LabelRender;
use Untek\Component\Web\Form\Libs\Renders\PasswordRender;
use Untek\Component\Web\Form\Libs\Renders\SelectRender;
use Untek\Component\Web\Form\Libs\Renders\TextareaRender;
use Untek\Component\Web\Form\Libs\Renders\TextRender;
use Untek\Component\Web\Html\Helpers\Html;

class FormRender
{

    private $formOptions = [];
    private $rowTemplate = '<div class="form-group required has-error">{label}{input}{hint}</div>';
    private $formView;
    private $tokenManager;
    private $renderDefinitions = [
        'hidden' => HiddenRender::class,
        'file' => FileRender::class,
        'text' => TextRender::class,
        'number' => TextRender::class,
        'textarea' => TextareaRender::class,
        'choice' => SelectRender::class,
        'select' => SelectRender::class,
        'password' => PasswordRender::class,
        'submit' => ButtonRender::class,
        'checkbox' => CheckboxRender::class,
        'hint' => HintRender::class,
        'label' => LabelRender::class,
        'formError' => FormErrorRender::class,
    ];

    public function __construct(FormView $formView, CsrfTokenManagerInterface $tokenManager)
    {
        /** @var FormView $child */
        foreach ($formView->children as $child) {
            if(ArrayHelper::getValue($child->vars, 'type') == 'file') {
                $this->addFormOption('enctype', 'multipart/form-data');
            }
        }
        $this->formView = $formView;
        $this->tokenManager = $tokenManager;
    }

    public function getFormView(): FormView
    {
        return $this->formView;
    }

    public function addFormOption(string $name, string $value = null)
    {
        $this->formOptions[$name] = $value;
    }

    public function beginFrom()
    {
        $formOptions = ArrayHelper::merge($this->formOptions, [
            'name' => $this->formView->vars['name'],
            'method' => $this->formView->vars['method'],
            
        ]);
        $html = Html::beginTag('form', $formOptions);
        $html .= $this->csrfTokenInput();
        return $html;
    }

    public function endFrom()
    {
        return Html::endForm();
    }

    public function errors()
    {
        $renderInstance = $this->createRender('formError');
        return $renderInstance->render();
    }

    public function row(string $name, string $type = null, array $options = [])
    {
        $params = [
            'label' => $this->label($name),
            'input' => $this->input($name, $type, $options),
            'hint' => $this->hint($name),
        ];
        return TemplateHelper::render($this->rowTemplate, $params);
    }

    public function input(string $name, string $type = null, array $options = [])
    {
        $renderInstance = $this->createRender($type, $name, $options);
        $renderInstance->setAttributeName($name);
        return $renderInstance->render();
    }

    public function renderDefinitions(): array
    {
        return $this->renderDefinitions;
    }

    public function addDefinition($definition): array
    {
        $this->renderDefinitions[] = $definition;
    }

    public function label($name)
    {
        return $this->input($name, 'label');
    }

    public function hint($name, $text = null)
    {
        return $this->input($name, 'hint');
    }

    private function createRender(?string $type, string $name = null, array $options = []): BaseRender
    {
        if ($type == null) {
            $type = $this->extractType($name);
        }
        $renderDefinition = $this->renderDefinitions[$type];
        /** @var BaseRender $renderInstance */
        $renderInstance = new $renderDefinition($this->formView);
        $renderInstance->setOptions($options);
        return $renderInstance;
    }

    private function csrfTokenInput(): string
    {
        $token = $this->tokenManager->getToken(getenv('CSRF_TOKEN_ID'));
        return Html::hiddenInput('csrfToken', $token->getValue());
    }

    private function extractType(string $name): string
    {
        /** @var FormView $childrenView */
        $childrenView = $this->formView->children[$name];
        $blockPrefixes = $childrenView->vars['block_prefixes'];
        if(count($blockPrefixes) == 3) {
            $type = $blockPrefixes[1];
        } elseif(count($blockPrefixes) == 4) {
            $type = $blockPrefixes[2];
        }
        return $type;
    }
}
