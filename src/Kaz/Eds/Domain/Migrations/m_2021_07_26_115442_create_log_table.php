<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Domain\Base\BaseCreateTableMigration;

class m_2021_07_26_115442_create_log_table extends BaseCreateTableMigration
{

    protected $tableName = 'eds_crl_log';
    protected $tableComment = '';

    public function tableStructure(Blueprint $table): void
    {
        $table->integer('id')->autoIncrement()->comment('Идентификатор');
        $table->integer('host_id')->comment('');
        $table->string('created_count')->comment('');
        $table->dateTime('created_at')->comment('Время создания');
    }
}