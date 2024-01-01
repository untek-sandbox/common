<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Domain\Base\BaseCreateTableMigration;

class m_2021_07_26_110801_create_crl_table extends BaseCreateTableMigration
{

    protected $tableName = 'eds_crl';
    protected $tableComment = '';

    public function tableStructure(Blueprint $table): void
    {
        $table->integer('id')->autoIncrement()->comment('Идентификатор');
        $table->integer('host_id')->comment('Идентификатор хоста');
        $table->string('key')->comment('Идентификатор сертификата');
        $table->dateTime('revoked_at')->comment('Время отзыва сертификата');
        $table->dateTime('created_at')->comment('Время создания');
        
        $table->unique(['host_id', 'key']);
        
        $this->addForeign($table, 'host_id', 'eds_host');
    }
}