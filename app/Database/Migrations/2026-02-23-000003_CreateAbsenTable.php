<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAbsenTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_absen' => [
                'type' => 'INT',
                'null' => false,
                'auto_increment' => true,
            ],
            'id_siswa' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'keterangan' => [
                'type' => 'ENUM',
                'constraint' => ['Hadir', 'Izin', 'Sakit', 'Alpa'],
                'null' => true,
                'default' => 'Alpa',
            ],
            'waktu' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'waktu_pulang' => [
                'type' => 'TIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id_absen');
        $this->forge->addKey('id_siswa');
        $this->forge->addForeignKey('id_siswa', 'siswa', 'id_siswa', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('absen', true, [
            'ENGINE' => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('absen', true);
    }
}
