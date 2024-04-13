<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;

use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\Actions\BaseAction;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\Actions\DeleteAction;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\Actions\RestoreAction;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\Actions\UpdateAction;
use Untek\Lib\Components\Status\Enums\StatusEnum;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\Actions\ViewAction;

class ActionFormatter extends BaseFormatter implements FormatterInterface
{

    public $baseUrl = '';
    public $actions = [];
    public $restorable = false;
    public $actionDefinitions = [];

    public function render($items)
    {
        $actions = [];
        $actionDefinitions = $this->actionDefinitions();
        foreach ($this->actions as $action) {
            $actionDefinition = $actionDefinitions[$action];
            /** @var BaseAction $actionInstance */
            $actionInstance = ClassHelper::createObject($actionDefinition);
            if (!isset($actionInstance->baseUrl)) {
                $actionInstance->baseUrl = $this->baseUrl;
            }
            $actionInstance->entity = $this->attributeEntity->getEntity();
            $actions[] = $actionInstance->run();
        }
        return '<div class="text-right">' . implode(' ', $actions) . '</div>';
    }

    private function actionDefinitions(): array
    {
        $actionDefinitions = ArrayHelper::merge($this->defaultActionDefinitions(), $this->actionDefinitions);
        if ($this->restorable) {
            $entity = $this->attributeEntity->getEntity();
            if ($entity->getStatusId() === StatusEnum::DELETED) {
                $actionDefinitions['delete'] = $actionDefinitions['restore'];
            }
        }
        return $actionDefinitions;
    }

    private function defaultActionDefinitions(): array
    {
        return [
            'view' => [
                'class' => ViewAction::class,
            ],
            'update' => [
                'class' => UpdateAction::class,
            ],
            'delete' => [
                'class' => DeleteAction::class,
            ],
            'restore' => [
                'class' => RestoreAction::class,
            ],
        ];
    }
}
