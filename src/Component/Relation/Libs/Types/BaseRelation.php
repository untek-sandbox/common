<?php

namespace Untek\Component\Relation\Libs\Types;

use Doctrine\Persistence\ObjectRepository;
use Psr\Container\ContainerInterface;
use Untek\Component\Relation\Interfaces\RelationInterface;
use Untek\Component\Relations\interfaces\CrudRepositoryInterface;
use Untek\Core\Code\Factories\PropertyAccess;
use Untek\Model\Query\Entities\Query;

abstract class BaseRelation implements RelationInterface
{

    /** @var string Имя связи, указываемое в методе with.
     * Если пустое, то берется из атрибута relationEntityAttribute
     */
    public $name;

    /** @var string Имя поля, в которое записывать вложенную сущность */
    public $relationEntityAttribute;

    /** @var string Имя первичного ключа связной таблицы */
    public $foreignAttribute = 'id';

    /** @var string Имя класса связного репозитория */
    public $foreignRepositoryClass;

    /** @var array Условие для присваивания связи, иногда нужно для полиморических связей */
    public $condition = [];

    /** @var callable Callback-метод для пост-обработки коллекции из связной таблицы */
    public $prepareCollection;

    /** @var Query Объект запроса для связного репозитория */
    public $query;
    protected $container;
    //private $cache = [];

    public $fromPath = null;

    abstract protected function loadRelation(array $collection): void;

    public function __construct(ContainerInterface $container)
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
        //$primaryKey = $foreignRepositoryInstance->primaryKey()[0];
        $criteria = [
            $this->foreignAttribute => $ids
        ];
        return $this->loadCollection($foreignRepositoryInstance, $ids, $criteria);
    }

    protected function loadCollection(ObjectRepository $foreignRepositoryInstance, array $ids, array $criteria): array
    {
        $collection = $foreignRepositoryInstance->findBy($criteria, null, count($ids));
        return $collection;
    }

    protected function getQuery(): Query
    {
        return $this->query ? $this->query : new Query;
    }

    protected function getRepositoryInstance(): ObjectRepository
    {
        return $this->container->get($this->foreignRepositoryClass);
    }
}
