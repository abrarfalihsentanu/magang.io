<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Leaves extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_leave' => [
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
            'jenis_cuti' => [
                'type' => 'ENUM',
                'constraint' => ['cuti', 'izin', 'sakit'],
            ],
            'tanggal_mulai' => [
                'type' => 'DATE',
            ],
            'tanggal_selesai' => [
                'type' => 'DATE',
            ],
            'jumlah_hari' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'alasan' => [
                'type' => 'TEXT',
            ],
            'dokumen_pendukung' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Surat sakit, dll',
            ],
            'status_approval' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
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
            'catatan_approval' => [
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
        $this->forge->addKey('id_leave', true);
        $this->forge->addKey(['id_user', 'status_approval']);
        $this->forge->addKey('tanggal_mulai');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->createTable('leaves');
    }

    public function down()
    {
        $this->forge->dropTable('leaves');
    }
}
