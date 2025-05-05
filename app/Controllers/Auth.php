<?php

namespace App\Controllers;

use App\Models\User_model;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User_model();
    }

    // Halaman login
    public function login()
    {
        // Jika sudah login, redirect ke halaman sesuai role jika diperlukan
        return view('auth/login');
    }

    // Proses login
    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->getUser($username);

        if (!$user) {
            return redirect()->back()->with('error', 'Username tidak ditemukan');
        }


        if (password_verify($password, $user['password'])) {
            if (strlen($user['password']) <= 32) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $this->userModel->update($user['id'], ['password' => $newHash]);
            }
            session()->set([
                'user_id'   => $user['id'],
                'username'  => $user['username'],
                'role'      => $user['role'],
                'logged_in' => true
            ]);
            return redirect()->to('/dashboard');
        }

        // Password salah
        return redirect()->back()->with('error', 'Password salah');
    }


    // Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/dashboard');
    }
}
