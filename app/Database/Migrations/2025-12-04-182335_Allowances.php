<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Allowances extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_allowance' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_period' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'total_hari_kerja' => [
                'type' => 'INT',
                'constraint' => 3,
                'comment' => 'Total hari kerja dalam periode',
            ],
            'total_hadir' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'total_alpha' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
            ],
            'total_izin' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
            ],
            'total_sakit' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
            ],
            'rate_per_hari' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'comment' => 'Nominal uang saku per hari (Rp 100.000)',
            ],
            'total_uang_saku' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'comment' => 'total_hadir * rate_per_hari',
            ],
            'nomor_rekening' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'nama_bank' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'atas_nama' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'status_pembayaran' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'paid'],
                'default' => 'pending',
            ],
            'tanggal_transfer' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'bukti_transfer' => [
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
        $this->forge->addKey('id_allowance', true);
        $this->forge->addUniqueKey(['id_period', 'id_user']);
        $this->forge->addKey(['id_user', 'status_pembayaran']);
        $this->forge->addForeignKey('id_period', 'allowance_periods', 'id_period', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('allowances');
    }

    public function down()
    {
        $this->forge->dropTable('allowances');
    }
}
