<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Infrastructure\Migration\Abstract\BaseCreateTableMigration;

class m_2024_01_18_081811_create_user_assigned_roles_table extends BaseCreateTableMigration
{

    protected $tableName = 'user_assigned_roles';
    protected $tableComment = '';

    public function tableStructure(Blueprint $table): void
    {
          $table->integer('id')->autoIncrement()->comment('Идентификатор');
          $table->integer('user_id')->comment('');
          $table->string('role')->comment('');

    }
}