<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AuditLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_log' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'comment' => 'create, update, delete, login, logout, approve, etc',
            ],
            'module' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'attendance, activity, kpi, allowance, etc',
            ],
            'record_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'old_data' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'new_data' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id_log', true);
        $this->forge->addKey(['id_user', 'created_at']);
        $this->forge->addKey('module');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->createTable('audit_logs');
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs');
    }
}
