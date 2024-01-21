<?php

namespace Untek\Database\Base\Domain\Traits;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\DotEnv\Domain\Libs\DotEnv;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Base\Domain\Libs\TableAlias;

trait TableNameTrait
{

    protected $tableName;

    abstract public function getCapsule(): Manager;

    public function connectionName()
    {
        return $this
            ->getCapsule()
            ->getConnectionNameByTableName($this->getTableName());
    }

    public function tableName(): string
    {
        DeprecateHelper::hardThrow();
        return $this->tableName;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function tableNameAlias(): string
    {
        return $this->encodeTableName($this->getTableName());
    }

    protected function getAlias(): TableAlias
    {
        return $this
            ->getCapsule()
            ->getAlias();
    }
    
    public function encodeTableName(string $sourceTableName, string $connectionName = null): string
    {
        $connectionName = $connectionName ?: $this->connectionName();
        $targetTableName = $this
            ->getCapsule()
            ->getAlias()
            ->encode($connectionName, $sourceTableName);
        return $targetTableName;
    }
}