<?php

namespace Untek\Component\Web\Menu\Domain\Repositories\File;

use Untek\Component\Web\Menu\Domain\Entities\MenuEntity;
use Untek\Component\Web\Menu\Domain\Interfaces\Repositories\MenuRepositoryInterface;
use Untek\Model\Components\FileRepository\Base\BaseFileCrudRepository;

class MenuRepository extends BaseFileCrudRepository implements MenuRepositoryInterface
{

    private $fileName;

    public function getEntityClass(): string
    {
        return MenuEntity::class;
    }

    public function setFileName( string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function fileName(): string
    {
        return $this->fileName;
    }
}
