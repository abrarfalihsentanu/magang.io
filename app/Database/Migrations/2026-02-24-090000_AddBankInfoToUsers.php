<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBankInfoToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'nomor_rekening' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'alamat',
            ],
            'nama_bank' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'nomor_rekening',
            ],
            'atas_nama' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'nama_bank',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'nomor_rekening');
        $this->forge->dropColumn('users', 'nama_bank');
        $this->forge->dropColumn('users', 'atas_nama');
    }
}
