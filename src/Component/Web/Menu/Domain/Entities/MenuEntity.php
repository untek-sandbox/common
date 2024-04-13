<?php

namespace Untek\Component\Web\Menu\Domain\Entities;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Model\Validator\Interfaces\ValidationByMetadataInterface;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Model\Entity\Interfaces\EntityIdInterface;

class MenuEntity implements ValidationByMetadataInterface, EntityIdInterface
{

    private $id = null;

    private $label = null;

    private $labelTranslate = null;

    private $url = null;

    private $route = null;

    private $module = null;

    private $controller = null;

    private $icon = null;

    private $active = null;

    private $activeRoutes = [];

    private $access = [];

    private $options = [];

    private $encode = true;

    private $visible = true;

    private $widget;

    private $items;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('id', new Assert\NotBlank);
        $metadata->addPropertyConstraint('label', new Assert\NotBlank);
        $metadata->addPropertyConstraint('url', new Assert\NotBlank);
        $metadata->addPropertyConstraint('icon', new Assert\NotBlank);
        $metadata->addPropertyConstraint('active', new Assert\NotBlank);
        $metadata->addPropertyConstraint('access', new Assert\NotBlank);
    }

    public function setId($value): void
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLabel(string $value): void
    {
        $this->label = $value;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getLabelTranslate(): ?array
    {
        return $this->labelTranslate;
    }

    public function setLabelTranslate(array $labelTranslate): void
    {
        $this->labelTranslate = $labelTranslate;
    }

    public function setUrl(string $value): void
    {
        $this->url = $value;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    public function getModule()
    {
        if (empty($this->module) && !empty($this->route)) {
            $arr = explode('/', $this->route);
            return $arr[0] ?? null;
        }
        return $this->module;
    }

    public function setModule($module): void
    {
        $this->module = $module;
    }

    public function getController()
    {
        if (empty($this->controller) && !empty($this->route)) {
            $arr = explode('/', $this->route);
            return $arr[1] ?? null;
        }
        return $this->controller;
    }

    public function setController($controller): void
    {
        $this->controller = $controller;
    }

    public function setIcon(string $value): void
    {
        $this->icon = $value;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setActive(bool $value): void
    {
        $this->active = $value;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function getActiveRoutes(): array
    {
        return $this->activeRoutes;
    }

    public function setActiveRoutes(array $activeRoutes): void
    {
        $this->activeRoutes = $activeRoutes;
    }

    public function setAccess(array $value): void
    {
        $this->access = $value;
    }

    public function getAccess(): array
    {
        return $this->access;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function addOption(string $key, $value): void
    {
        $this->options[$key] = $value;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function getEncode(): bool
    {
        return $this->encode;
    }

    public function setEncode(bool $encode): void
    {
        $this->encode = $encode;
    }

    public function getVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    public function getWidget()
    {
        return $this->widget;
    }

    public function setWidget($widget): void
    {
        $this->widget = $widget;
    }

    /**
     * @return \Untek\Core\Collection\Interfaces\Enumerable | MenuEntity[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items): void
    {
        if (is_array($items)) {
            $items = CollectionHelper::create(MenuEntity::class, $items);
        }
        $this->items = $items;
    }
}
