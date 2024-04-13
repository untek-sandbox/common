<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Infrastructure\Migration\Abstract\BaseColumnMigration;

class m_2020_03_16_102260_add_column_expired_in_credential_table extends BaseColumnMigration
{

    protected $tableName = 'user_credential';

    public function tableStructure(Blueprint $table): void
    {
        $table->dateTime('created_at')->nullable()->comment('Время создания');
        $table->dateTime('expired_at')->nullable()->comment('Годен до...');
    }
}