<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KpiIndicators extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_indicator' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_indicator' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'kategori' => [
                'type' => 'ENUM',
                'constraint' => ['kehadiran', 'aktivitas', 'project'],
            ],
            'bobot' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'comment' => 'Bobot dalam persen (0-100)',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'formula' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Cara perhitungan indikator',
            ],
            'is_auto_calculate' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Auto hitung dari sistem atau manual input',
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
        $this->forge->addKey('id_indicator', true);
        $this->forge->addKey(['kategori', 'is_active']);
        $this->forge->createTable('kpi_indicators');
    }

    public function down()
    {
        $this->forge->dropTable('kpi_indicators');
    }
}
