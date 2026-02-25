<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ArchivedInterns extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_archive' => [
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
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'nama_lengkap' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'nik' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'divisi' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'periode_mulai' => [
                'type' => 'DATE',
            ],
            'periode_selesai' => [
                'type' => 'DATE',
            ],
            'total_hari_hadir' => [
                'type' => 'INT',
                'constraint' => 5,
            ],
            'total_hari_kerja' => [
                'type' => 'INT',
                'constraint' => 5,
            ],
            'persentase_kehadiran' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
            ],
            'final_kpi_score' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'final_rank' => [
                'type' => 'INT',
                'constraint' => 5,
            ],
            'total_uang_saku' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'summary_data' => [
                'type' => 'JSON',
                'comment' => 'JSON summary of attendance, activities, projects, etc',
            ],
            'archived_at' => [
                'type' => 'DATETIME',
            ],
            'archived_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_archive', true);
        $this->forge->addKey('id_intern');
        $this->forge->addKey(['periode_mulai', 'periode_selesai']);
        $this->forge->addForeignKey('id_intern', 'interns', 'id_intern', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('archived_by', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('archived_interns');
    }

    public function down()
    {
        $this->forge->dropTable('archived_interns');
    }
}
