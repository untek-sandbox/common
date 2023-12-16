<?php

namespace Untek\Component\Relation\Libs\Types;

use Doctrine\Persistence\ObjectRepository;
use Psr\Container\ContainerInterface;
use Untek\Component\Relation\Interfaces\RelationInterface;
use Untek\Component\Relations\interfaces\CrudRepositoryInterface;
use Untek\Core\Code\Factories\PropertyAccess;

abstract class BaseRelation implements RelationInterface
{

    /** Связующее поле */
    public string $relationAttribute;

    /** @var string Имя связи, указываемое в методе with.
     * Если пустое, то берется из атрибута relationEntityAttribute
     */
    public string $name;

    /** @var string Имя поля, в которое записывать вложенную сущность */
    public string $relationEntityAttribute;

    /** @var string Имя первичного ключа связной таблицы */
    public string $foreignAttribute = 'id';

    /** @var string Имя класса связного репозитория */
    public string $foreignRepositoryClass;

    /** @var array Условие для присваивания связи, иногда нужно для полиморических связей */
    public array $condition = [];

    /** @var callable Callback-метод для пост-обработки коллекции из связной таблицы */
    public $prepareCollection;

    protected ContainerInterface $container;

    public array $relations = [];

    public $fromPath = null;

    abstract protected function loadCollection(ObjectRepository $foreignRepositoryInstance, array $criteria): array;

    abstract protected function loadRelation(array $collection): void;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function run(array $collection): void
    {
        $this->loadRelation($collection);
        $collection = $this->prepareCollection($collection);
    }

    protected function getValueFromPath($value)
    {
        if ($this->fromPath) {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $value = $propertyAccessor->getValue($value, $this->fromPath);
        }
        return $value;
    }

    protected function prepareCollection(array $collection)
    {
        if ($this->prepareCollection) {
            call_user_func($this->prepareCollection, $collection);
        }
    }

    protected function loadRelationByIds(array $ids): array
    {
        $foreignRepositoryInstance = $this->getRepositoryInstance();
        $criteria = [
            $this->foreignAttribute => $ids
        ];
        return $this->loadCollection($foreignRepositoryInstance, $criteria);
    }

    protected function getRepositoryInstance(): ObjectRepository
    {
        return $this->container->get($this->foreignRepositoryClass);
    }
}
