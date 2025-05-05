<?php

namespace App\Controllers;

use App\Models\User_model;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User_model();

        helper('auth');
        check_login();

        $except = ['show', 'newpass']; // metode yang boleh diakses siapa saja
        $currentMethod = service('router')->methodName();

        if (!in_array($currentMethod, $except)) {
            check_role(['admin']);
        }
    }

    public function index()
    {
        $data['userList'] = $this->userModel->findAll();
        return view('user/index', $data);
    }

    public function create()
    {
        return view('user/form');
    }

    public function save()
    {
        $rules = [
            'name' => 'required',
            'username' => 'required|is_unique[users.username]',
            'role' => 'required|in_list[admin,asisten]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $password = password_hash($this->request->getPost('username'), PASSWORD_DEFAULT);

        $data = [
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'password' => $password,
            'role'     => $this->request->getPost('role')
        ];

        $this->userModel->save($data);

        return redirect()->to('/user')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = $this->userModel->where('id', $id)->first();
        if (!$user) return redirect()->to('/user')->with('error', 'User tidak ditemukan');

        return view('user/form', ['user' => $user]);
    }

    public function update($id)
    {
        $user = $this->userModel->where('id', $id)->first();
        if (!$user) return redirect()->to('/user')->with('error', 'User tidak ditemukan');

        $rules = [
            'name' => 'required',
            'username' => 'required|is_unique[users.username,id,' . $user['id'] . ']',
            'role' => 'required|in_list[admin,asisten]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $data = [
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role')
        ];

        $this->userModel->update($user['id'], $data);

        return redirect()->to('/user')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = $this->userModel->where('id', $id)->first();
        if (!$user) return redirect()->to('/user')->with('error', 'User tidak ditemukan');

        $this->userModel->delete($user['id']);
        return redirect()->to('/user')->with('success', 'User berhasil dihapus');
    }

    public function reset($id)
    {
        $user = $this->userModel->where('id', $id)->first();
        if ($user) {
            $hash = $user['username'];
            $this->userModel->update($user['id'], ['password' => $hash]);
            return redirect()->to('/user')->with('success', 'Password berhasil direset');
        } else {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan');
        }
    }

    public function show($username)
    {
        $user = $this->userModel->where('username', $username)->first();
        if (!$user) return redirect()->to('/user')->with('error', 'User tidak ditemukan');

        return view('user/show', ['user' => $user]);
    }

    public function newpass($username)
    {
        $user = $this->userModel->where('username', $username)->first();

        if (!$user) {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan');
        }

        $old_password = $this->request->getPost('old_password');

        $rules = [
            'old_password'     => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        if (!password_verify($old_password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password lama tidak sesuai');
        }

        $newHash = $this->request->getPost('new_password');
        $this->userModel->update($user['id'], ['password' => $newHash]);

        return redirect()->to('/user/show/' . $username)->with('success', 'Password berhasil diperbarui');
    }
}
