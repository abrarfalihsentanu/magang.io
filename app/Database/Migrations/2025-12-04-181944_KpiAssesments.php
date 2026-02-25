<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KpiAssesments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_assessment' => [
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
            'id_indicator' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'bulan' => [
                'type' => 'INT',
                'constraint' => 2,
                'comment' => '1-12',
            ],
            'tahun' => [
                'type' => 'YEAR',
            ],
            'nilai_raw' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Nilai mentah sebelum dikalikan bobot',
            ],
            'nilai_weighted' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Nilai setelah dikalikan bobot',
            ],
            'penilai_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addKey('id_assessment', true);
        $this->forge->addUniqueKey(['id_user', 'id_indicator', 'bulan', 'tahun']);
        $this->forge->addKey(['id_user', 'bulan', 'tahun']);
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_indicator', 'kpi_indicators', 'id_indicator', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('penilai_id', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->createTable('kpi_assessments');
    }

    public function down()
    {
        $this->forge->dropTable('kpi_assessments');
    }
}
