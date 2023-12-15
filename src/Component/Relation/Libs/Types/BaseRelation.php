<?php

namespace Untek\Component\Relation\Libs\Types;

use Doctrine\Persistence\ObjectRepository;
use Psr\Container\ContainerInterface;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Model\Shared\Interfaces\FindAllInterface;
use Untek\Core\Code\Factories\PropertyAccess;
use Untek\Model\Query\Entities\Query;
use Untek\Model\Query\Entities\Where;
use Untek\Component\Relation\Interfaces\RelationInterface;
use Untek\Component\Relations\interfaces\CrudRepositoryInterface;

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

    abstract protected function loadRelation(Enumerable $collection): void;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run(Enumerable|array $collection): void
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

    protected function prepareCollection(Enumerable|array $collection)
    {
        if ($this->prepareCollection) {
            call_user_func($this->prepareCollection, $collection);
        }
    }

    protected function loadRelationByIds(array $ids)//: Enumerable
    {
        $foreignRepositoryInstance = $this->getRepositoryInstance();
        //$primaryKey = $foreignRepositoryInstance->primaryKey()[0];
        $query = $this->getQuery();
        $query->whereNew(new Where($this->foreignAttribute, $ids));
        //$query->andWhere(['in', ]);
        return $this->loadCollection($foreignRepositoryInstance, $ids, $query);
    }

    protected function loadCollection(ObjectRepository $foreignRepositoryInstance, array $ids, Query $query)//: Enumerable
    {
        // todo: костыль, надо проверить наверняка
        /*if (get_called_class() != OneToManyRelation::class) {
            $query->limit(count($ids));
        }*/
//        dd($query->getWhereNew());
        $query->limit(count($ids));

        $criteria = [];
        foreach ($query->getWhereNew() as $where) {
            $criteria[$where->column] = $where->value;
        }

        $collection = $foreignRepositoryInstance->findBy($criteria);
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
