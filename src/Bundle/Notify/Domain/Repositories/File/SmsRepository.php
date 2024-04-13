<?php

namespace Untek\Bundle\Notify\Domain\Repositories\File;

use Untek\Bundle\Notify\Domain\Entities\SmsEntity;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\SmsRepositoryInterface;
use Untek\Model\Components\FileRepository\Base\BaseLoopedFileRepository;

class SmsRepository extends BaseLoopedFileRepository implements SmsRepositoryInterface
{

    public function tableName(): string
    {
        return 'notify_sms';
    }

    public function getEntityClass(): string
    {
        return SmsEntity::class;
    }

    public function send(SmsEntity $smsEntity)
    {
        $this->insert($smsEntity);
    }
}
