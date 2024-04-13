<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Domain\Base\BaseCreateTableMigration;

class m_2021_06_28_164642_create_doc_x_table extends BaseCreateTableMigration
{

    protected $tableName = 'office_doc_x';
    protected $tableComment = '';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');
            $table->string('file_name')->comment('');
            $table->string('props')->comment('');
            $table->string('word')->comment('');
            $table->string('rels')->comment('');
            $table->string('types')->comment('');
        };
    }
}