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
                // password: arnama (bcrypt hash dari SQL asli)
                'password' => '$2y$10$bKrnNGrBwks.fNOzPqUxPeZ.Edquw8KWlRF/GwNtwmkKLeh.jQWgG',
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
