<?php
namespace App\Models;
use CodeIgniter\Model;

class Auth_model extends Model

{
    protected $table = 'user';

    public function cekLogin($username) {
        $query = $this->table('users')
            ->where('username', $username)
            ->countAll();
        if ($query > 0) {
            $hasil = $this->table('users')
                ->where('username', $username)
                ->limit(1)
                ->get()
                ->getRowArray();
        } else {
            $hasil = array();
        }
        return $hasil;
    }

    
} 