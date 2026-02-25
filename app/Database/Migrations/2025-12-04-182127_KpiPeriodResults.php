<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KpiPeriodResults extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_period_result' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_intern' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'periode_mulai' => [
                'type' => 'DATE',
            ],
            'periode_selesai' => [
                'type' => 'DATE',
            ],
            'avg_total_score' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Rata-rata KPI selama periode',
            ],
            'final_rank' => [
                'type' => 'INT',
                'constraint' => 5,
                'comment' => 'Ranking akhir periode',
            ],
            'is_best_intern' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'sertifikat_file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->addKey('id_period_result', true);
        $this->forge->addUniqueKey('id_intern');
        $this->forge->addKey('final_rank');
        $this->forge->addForeignKey('id_intern', 'interns', 'id_intern', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kpi_period_results');
    }

    public function down()
    {
        $this->forge->dropTable('kpi_period_results');
    }
}
