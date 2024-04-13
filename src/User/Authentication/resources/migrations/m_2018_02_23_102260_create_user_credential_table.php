<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Infrastructure\Migration\Abstract\BaseCreateTableMigration;

class m_2018_02_23_102260_create_user_credential_table extends BaseCreateTableMigration
{

    protected $tableName = 'user_credential';
    protected $tableComment = 'Аутентификация пользователя';

    public function tableStructure(Blueprint $table): void
    {
        $table->integer('id')->autoIncrement()->comment('Идентификатор');
        $table->integer('user_id')->comment('ID учетной записи');
        $table->string('type')->comment('Тип аутентификации');
        $table->string('credential')->comment('Учетная запись');
        $table->string('validation')->comment('Хэш пароля');

        $table->unique(['type', 'credential']);

        $this->addForeign($table, 'user_id', 'user_identity');
    }

}
