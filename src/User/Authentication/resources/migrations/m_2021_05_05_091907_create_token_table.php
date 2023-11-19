<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Infrastructure\Migration\Abstract\BaseCreateTableMigration;

class m_2021_05_05_091907_create_token_table extends BaseCreateTableMigration
{

    protected $tableName = 'user_token';
    protected $tableComment = 'Токен пользователя';

    public function tableStructure(Blueprint $table): void
    {
        $table->integer('id')->autoIncrement()->comment('Идентификатор');
        $table->integer('identity_id')->comment('ID учетной записи');
        $table->string('type')->comment('Тип токена');
        $table->string('value')->comment('Значение токена');
        $table->dateTime('created_at')->comment('Время создания');
        $table->dateTime('expired_at')->nullable()->comment('Время истечения срока годности');

        $table->unique(['type', 'value']);

        $this->addForeign($table, 'identity_id', 'user_identity');
    }
}