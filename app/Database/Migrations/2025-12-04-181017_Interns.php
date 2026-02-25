<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Interns extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_intern' => [
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
            'id_mentor' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID user yang jadi mentor',
            ],
            'universitas' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'jurusan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'periode_mulai' => [
                'type' => 'DATE',
            ],
            'periode_selesai' => [
                'type' => 'DATE',
            ],
            'durasi_bulan' => [
                'type' => 'INT',
                'constraint' => 3,
                'comment' => 'Durasi magang dalam bulan',
            ],
            'status_magang' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'completed', 'terminated'],
                'default' => 'active',
            ],
            'dokumen_surat_magang' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'catatan' => [
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
        $this->forge->addKey('id_intern', true);
        $this->forge->addKey('id_user');
        $this->forge->addKey('id_mentor');
        $this->forge->addKey('status_magang');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_mentor', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->createTable('interns');
    }

    public function down()
    {
        $this->forge->dropTable('interns');
    }
}
