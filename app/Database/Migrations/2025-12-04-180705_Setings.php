<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_setting' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'setting_key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'setting_value' => [
                'type' => 'TEXT',
            ],
            'setting_type' => [
                'type' => 'ENUM',
                'constraint' => ['string', 'number', 'json', 'boolean', 'date'],
                'default' => 'string',
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'general, attendance, allowance, kpi',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_editable' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_setting', true);
        $this->forge->addKey('category');
        $this->forge->createTable('settings');
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
