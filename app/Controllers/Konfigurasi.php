<?php

namespace App\Controllers;

use App\Models\Konfigurasi_model;
use CodeIgniter\Controller;

class Konfigurasi extends BaseController
{
    protected $konfigurasiModel;

    public function __construct()
    {
        $this->konfigurasiModel = new Konfigurasi_model();
    }

    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Konfigurasi Sistem',
            'konfigurasi' => $this->konfigurasiModel->findAll(),
        ];

        return view('konfigurasi/index', $data);
    }

    public function update()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $rules = [
            'value' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id = $this->request->getPost('id');
        $value = $this->request->getPost('value');

        // Jika value adalah array (dari input dinamis), gabungkan dengan koma
        if (is_array($value)) {
            $value = implode(', ', array_filter(array_map('trim', $value)));
        }

        $this->konfigurasiModel->update($id, ['value' => $value]);

        return redirect()->to('/konfigurasi')->with('success', 'Konfigurasi berhasil diperbarui');
    }
}
