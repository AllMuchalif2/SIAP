<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 0,
                'username' => 'arnama',
                'name' => 'arnama',
                // password: arnama
                'password' => '$2y$10$L.iFxHLh4upGP1cVDBFfx.Epl2ldr9PXTsuVdqMW4O2TwCOGfeiKS',
                'role' => 'admin',
            ],
            [
                'id' => 1,
                'username' => 'asisten',
                'name' => 'asisten',
                // password: asisten
                'password' => '$2y$10$5SZd5jbPkrPNiZg6g/0zNe9JZW9OjGicnXUZkS1iye.RDPyiMXXJm',
                'role' => 'asisten',
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
