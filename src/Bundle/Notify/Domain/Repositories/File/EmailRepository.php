<?php

namespace Untek\Bundle\Notify\Domain\Repositories\File;

use Untek\Bundle\Notify\Domain\Entities\EmailEntity;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\EmailRepositoryInterface;
use Untek\Model\Components\FileRepository\Base\BaseLoopedFileRepository;

class EmailRepository extends BaseLoopedFileRepository implements EmailRepositoryInterface
{

    public function tableName(): string
    {
        return 'notify_email';
    }

    public function getEntityClass(): string
    {
        return EmailEntity::class;
    }

    public function send(EmailEntity $emailEntity)
    {
        $this->insert($emailEntity);
    }
}
