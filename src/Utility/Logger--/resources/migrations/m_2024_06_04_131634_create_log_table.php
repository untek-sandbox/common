<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Infrastructure\Migration\Abstract\BaseCreateTableMigration;

class m_2024_06_04_131634_create_log_table extends BaseCreateTableMigration
{

    protected $tableName = 'log';

    public function tableStructure(Blueprint $table): void
    {
        $table->integer('level');
        $table->string('channel');
        $table->string('message');
        $table->dateTime('datetime');
        $table->string('formatted');
        $table->string('extra');
        $table->string('context');
    }
}