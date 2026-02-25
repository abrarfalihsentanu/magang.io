<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class WeeklyProjects extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_project' => [
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
            'week_number' => [
                'type' => 'INT',
                'constraint' => 2,
                'comment' => 'Week of year (1-52)',
            ],
            'tahun' => [
                'type' => 'YEAR',
            ],
            'periode_mulai' => [
                'type' => 'DATE',
            ],
            'periode_selesai' => [
                'type' => 'DATE',
            ],
            'judul_project' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'tipe_project' => [
                'type' => 'ENUM',
                'constraint' => ['inisiatif', 'assigned'],
                'comment' => 'Inisiatif sendiri atau ditugaskan',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
            ],
            'progress' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
                'comment' => 'Progress 0-100%',
            ],
            'deliverables' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Hasil/output project',
            ],
            'attachment' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'self_rating' => [
                'type' => 'DECIMAL',
                'constraint' => '3,2',
                'null' => true,
                'comment' => 'Self assessment 1-5',
            ],
            'status_submission' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'submitted', 'assessed'],
                'default' => 'draft',
            ],
            'mentor_rating' => [
                'type' => 'DECIMAL',
                'constraint' => '3,2',
                'null' => true,
                'comment' => 'Rating dari mentor 1-5',
            ],
            'assessed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'assessed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'feedback_mentor' => [
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
        $this->forge->addKey('id_project', true);
        $this->forge->addKey(['id_user', 'week_number', 'tahun']);
        $this->forge->addKey('status_submission');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assessed_by', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->createTable('weekly_projects');
    }

    public function down()
    {
        $this->forge->dropTable('weekly_projects');
    }
}
