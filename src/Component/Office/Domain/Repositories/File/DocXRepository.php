<?php

namespace Untek\Component\Office\Domain\Repositories\File;

use Untek\Component\Office\Domain\Entities\DocXEntity;
use Untek\Component\Office\Domain\Interfaces\Repositories\DocXRepositoryInterface;

class DocXRepository implements DocXRepositoryInterface
{

    public function tableName() : string
    {
        return 'office_doc_x';
    }

    public function getEntityClass() : string
    {
        return DocXEntity::class;
    }


}

