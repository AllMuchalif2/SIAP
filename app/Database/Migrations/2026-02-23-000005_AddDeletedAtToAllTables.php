<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToAllTables extends Migration
{
    public function up()
    {
        // Tambah deleted_at ke tabel siswa
        $this->forge->addColumn('siswa', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
                'after' => 'status',
            ],
        ]);

        // Tambah deleted_at ke tabel users
        $this->forge->addColumn('users', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
                'after' => 'role',
            ],
        ]);

        // Tambah deleted_at ke tabel absen
        $this->forge->addColumn('absen', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
                'after' => 'waktu_pulang',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', 'deleted_at');
        $this->forge->dropColumn('users', 'deleted_at');
        $this->forge->dropColumn('absen', 'deleted_at');
    }
}
