<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AllowanceSlips extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_slip' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_allowance' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'nomor_slip' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'generated_at' => [
                'type' => 'DATETIME',
            ],
            'generated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_slip', true);
        $this->forge->addKey('id_allowance');
        $this->forge->addForeignKey('id_allowance', 'allowances', 'id_allowance', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('generated_by', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->createTable('allowance_slips');
    }

    public function down()
    {
        $this->forge->dropTable('allowance_slips');
    }
}
