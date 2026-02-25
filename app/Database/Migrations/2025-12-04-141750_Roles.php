<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Roles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_role' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_role' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'kode_role' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'comment' => 'admin, mentor, hr, finance, intern',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'permissions' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'JSON array of permissions',
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_role', true);
        $this->forge->addKey('kode_role');
        $this->forge->createTable('roles');
    }

    public function down()
    {
        $this->forge->dropTable('roles');
    }
}
