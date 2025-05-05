<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableAbsen extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id_absen'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'id_siswa'       => [
                'type'       => 'char',
                'constraint' => 8,
            ],
            'tanggal'       => [
                'type'       => 'DATE',
            ],
            'keterangan'       => [
                'type'       => 'ENUM',
                'constraint' => ['Hadir', 'Izin', 'Sakit', 'Alpa'],
            ],
            'waktu' => [
                'type' => 'TIME',
            ],
            'created_at'       => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at'       => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id_absen', true);
        $this->forge->addForeignKey('id_siswa', 'siswa', 'id_siswa', 'CASCADE', 'CASCADE');
        $this->forge->createTable('absen');
    }

    public function down()
    {
        //
    }
}
