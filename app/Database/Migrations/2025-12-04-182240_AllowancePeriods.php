<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AllowancePeriods extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_period' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_periode' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'comment' => 'e.g. "Periode 15 Jan - 15 Feb 2025"',
            ],
            'tanggal_mulai' => [
                'type' => 'DATE',
                'comment' => 'Tanggal 15 bulan ini',
            ],
            'tanggal_selesai' => [
                'type' => 'DATE',
                'comment' => 'Tanggal 15 bulan depan',
            ],
            'tanggal_pembayaran' => [
                'type' => 'DATE',
                'comment' => 'Tanggal 25 bulan setelah periode selesai',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'calculated', 'approved', 'paid'],
                'default' => 'draft',
            ],
            'total_pemagang' => [
                'type' => 'INT',
                'constraint' => 5,
                'default' => 0,
            ],
            'total_nominal' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'calculated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'calculated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'paid_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addKey('id_period', true);
        $this->forge->addKey(['tanggal_mulai', 'tanggal_selesai']);
        $this->forge->addKey('status');
        $this->forge->addForeignKey('calculated_by', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('paid_by', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->createTable('allowance_periods');
    }

    public function down()
    {
        $this->forge->dropTable('allowance_periods');
    }
}
