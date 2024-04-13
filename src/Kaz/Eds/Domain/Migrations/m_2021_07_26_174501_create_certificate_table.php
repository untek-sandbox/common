<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Domain\Base\BaseCreateTableMigration;

class m_2021_07_26_174501_create_certificate_table extends BaseCreateTableMigration
{

    protected $tableName = 'eds_certificate';
    protected $tableComment = '';

    public function tableStructure(Blueprint $table): void
    {
        $table->integer('id')->autoIncrement()->comment('Идентификатор');
        $table->integer('host_id')->comment('Идентификатор хоста');
        $table->string('key')->comment('Идентификатор сертификата');
        $table->text('content')->comment('Сертификат в формате PEM');
        $table->string('type')->comment('Тип (ca, issuer, subject)');

        $table->unique(['host_id', 'key']);

        $this->addForeign($table, 'host_id', 'eds_host');
    }
}