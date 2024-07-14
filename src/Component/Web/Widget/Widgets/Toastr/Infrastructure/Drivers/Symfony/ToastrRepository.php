<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Drivers\Symfony;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Model\ToastrEntity;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrRepositoryInterface;
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
