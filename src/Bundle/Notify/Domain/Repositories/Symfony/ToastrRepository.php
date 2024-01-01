<?php

namespace Untek\Bundle\Notify\Domain\Repositories\Symfony;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Untek\Bundle\Notify\Domain\Entities\ToastrEntity;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\ToastrRepositoryInterface;
use Untek\Model\Validator\Helpers\ValidationHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\EntityManager\Traits\EntityManagerAwareTrait;

class ToastrRepository implements ToastrRepositoryInterface
{

    use EntityManagerAwareTrait;

    private static $all = [];
    private $session;

    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->setEntityManager($em);
        $this->session = $session;
    }

    public function create(ToastrEntity $toastrEntity)
    {
        ValidationHelper::validateEntity($toastrEntity);
        self::$all[] = $toastrEntity;
        $this->sync();
    }

    public function findAll(): Enumerable
    {
        $items = $this->session->get('flash-alert', []);
        return new Collection($items);
    }

    public function clear()
    {
        self::$all[] = [];
        $this->session->remove('flash-alert');
    }

    private function sync()
    {
        $this->session->set('flash-alert', self::$all);
    }
}
