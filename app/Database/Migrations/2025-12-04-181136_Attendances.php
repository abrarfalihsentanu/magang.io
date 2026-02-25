<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Attendances extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_attendance' => [
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
            'jam_masuk' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'jam_keluar' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'latitude_masuk' => [
                'type' => 'DECIMAL',
                'constraint' => '10,7',
                'null' => true,
            ],
            'longitude_masuk' => [
                'type' => 'DECIMAL',
                'constraint' => '10,7',
                'null' => true,
            ],
            'distance_masuk' => [
                'type' => 'INT',
                'constraint' => 6,
                'null' => true,
                'comment' => 'Distance from office in meters',
            ],
            'foto_masuk' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'latitude_keluar' => [
                'type' => 'DECIMAL',
                'constraint' => '10,7',
                'null' => true,
            ],
            'longitude_keluar' => [
                'type' => 'DECIMAL',
                'constraint' => '10,7',
                'null' => true,
            ],
            'distance_keluar' => [
                'type' => 'INT',
                'constraint' => 6,
                'null' => true,
            ],
            'foto_keluar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['hadir', 'terlambat', 'izin', 'sakit', 'alpha', 'cuti'],
                'default' => 'alpha',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_manual' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'True jika input manual oleh admin',
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
        $this->forge->addKey('id_attendance', true);
        $this->forge->addUniqueKey(['id_user', 'tanggal']);
        $this->forge->addKey(['tanggal', 'status']);
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('attendances');
    }

    public function down()
    {
        $this->forge->dropTable('attendances');
    }
}
