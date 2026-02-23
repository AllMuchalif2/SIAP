<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAbsensiLogTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'null' => false,
                'auto_increment' => true,
            ],
            'id_absen' => [
                'type' => 'INT',
                'null' => true,
            ],
            'id_siswa' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'nama_siswa' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'keterangan_lama' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'keterangan_baru' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'waktu_lama' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'waktu_baru' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'waktu_pulang_lama' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'waktu_pulang_baru' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('absensi_log', true, [
            'ENGINE' => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('absensi_log', true);
    }
}
