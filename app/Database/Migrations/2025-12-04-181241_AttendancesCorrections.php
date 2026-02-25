<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AttendancesCorrections extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_correction' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_attendance' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'tanggal_koreksi' => [
                'type' => 'DATE',
            ],
            'jenis_koreksi' => [
                'type' => 'ENUM',
                'constraint' => ['masuk', 'keluar', 'both'],
            ],
            'jam_masuk_baru' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'jam_keluar_baru' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'alasan' => [
                'type' => 'TEXT',
            ],
            'bukti_foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
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
        $this->forge->addKey('id_correction', true);
        $this->forge->addKey(['id_user', 'status_approval']);
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_attendance', 'attendances', 'id_attendance', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->createTable('attendance_corrections');
    }

    public function down()
    {
        $this->forge->dropTable('attendance_corrections');
    }
}
