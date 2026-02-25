<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DailyActivities extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_activity' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jam_mulai' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'jam_selesai' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'judul_aktivitas' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
            ],
            'kategori' => [
                'type' => 'ENUM',
                'constraint' => ['learning', 'task', 'meeting', 'training', 'other'],
                'default' => 'task',
            ],
            'attachment' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status_approval' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'submitted', 'approved', 'rejected'],
                'default' => 'draft',
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'catatan_mentor' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('id_activity', true);
        $this->forge->addKey(['id_user', 'tanggal']);
        $this->forge->addKey('status_approval');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->createTable('daily_activities');
    }

    public function down()
    {
        $this->forge->dropTable('daily_activities');
    }
}
