<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username','name', 'password', 'role'];
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function getUser($username)
    {
        return $this->where('username', $username)->first();
    }
    public function isAdmin($userId)
    {
        $user = $this->find($userId);
        return isset($user['role']) && $user['role'] === 'admin';
    }

    public function isAsisten($userId)
    {
        $user = $this->find($userId);
        return isset($user['role']) && $user['role'] === 'asisten';
    }

    public function updateUser($userId, array $data)
    {
        return $this->update($userId, $data);
    }

    public function deleteUser($userId)
    {
        return $this->delete($userId);
    }
}
