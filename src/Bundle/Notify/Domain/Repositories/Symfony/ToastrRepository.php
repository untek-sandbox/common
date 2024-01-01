<?php

namespace Untek\Bundle\Notify\Domain\Repositories\Symfony;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Untek\Bundle\Notify\Domain\Entities\ToastrEntity;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\ToastrRepositoryInterface;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Model\Validator\Helpers\ValidationHelper;

class ToastrRepository implements ToastrRepositoryInterface
{

    private static $all = [];
    private $session;

    public function __construct(
        SessionInterface $session
    )
    {
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
