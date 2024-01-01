<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Domain\Base\BaseCreateTableMigration;

class m_2021_07_26_110703_create_host_table extends BaseCreateTableMigration
{

    protected $tableName = 'eds_host';
    protected $tableComment = '';

    public function tableStructure(Blueprint $table): void
    {
        $table->integer('id')->autoIncrement()->comment('Идентификатор');
        $table->string('title')->comment('');
        $table->string('crl_url')->comment('');
        $table->string('ca_url')->comment('');
    }
}