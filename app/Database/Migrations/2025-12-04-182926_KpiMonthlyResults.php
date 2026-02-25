<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KpiMonthlyResults extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_result' => [
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
            'bulan' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
            'tahun' => [
                'type' => 'YEAR',
            ],
            'total_score' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Total KPI score bulan ini',
            ],
            'rank_bulan_ini' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
                'comment' => 'Ranking di bulan ini',
            ],
            'kategori_performa' => [
                'type' => 'ENUM',
                'constraint' => ['excellent', 'good', 'average', 'below_average', 'poor'],
                'null' => true,
            ],
            'is_finalized' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Sudah di-finalize atau masih draft',
            ],
            'finalized_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'finalized_by' => [
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
        $this->forge->addKey('id_result', true);
        $this->forge->addUniqueKey(['id_user', 'bulan', 'tahun']);
        $this->forge->addKey(['bulan', 'tahun', 'rank_bulan_ini']);
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('finalized_by', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->createTable('kpi_monthly_results');
    }

    public function down()
    {
        $this->forge->dropTable('kpi_monthly_results');
    }
}
