<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKonfigurasiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => true,
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
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
        $this->forge->addKey('id', true);
        $this->forge->createTable('konfigurasi');

        // Insert default value
        $db = \Config\Database::connect();
        $db->table('konfigurasi')->insert([
            'slug' => 'ip_allowed',
            'value' => '127.0.0.1, ::1',
            'description' => 'Daftar IP yang diperbolehkan mengakses halaman absensi (pisahkan dengan koma)',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('konfigurasi');
    }
}
