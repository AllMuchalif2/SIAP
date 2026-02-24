<?php

namespace App\Models;

use CodeIgniter\Model;

class Konfigurasi_model extends Model
{
    protected $table = 'konfigurasi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['slug', 'value', 'description'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getKonfigurasi($slug = null)
    {
        if ($slug === null) {
            return $this->findAll();
        }

        return $this->where(['slug' => $slug])->first();
    }

    public function updateKonfigurasi($slug, $data)
    {
        return $this->where('slug', $slug)->set($data)->update();
    }
}
