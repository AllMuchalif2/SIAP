<?php

namespace App\Controllers;

use App\Models\Absen_model;
use CodeIgniter\Controller;
use App\Models\Siswa_model;

class Siswa extends Controller
{
    protected $siswaModel;
    protected $absenModel;

    public function __construct()
    {
        $this->siswaModel = new Siswa_model();
        $this->absenModel = new Absen_model();
    }

    public function index()
    {
        $siswaModel = new Siswa_model();
        $data['siswaList'] = $siswaModel->getSiswa(); // Mendapatkan daftar siswa dengan total absensi

        return view('siswa/index', $data); // Pastikan untuk menyesuaikan dengan view Anda
    }

    public function create()
    {
        echo view('siswa/form');
    }

    public function save()
    {
        $validation = \Config\Services::validation();
        $data = [
            'id_siswa' => $this->request->getPost('id_siswa'),
            'nama' => $this->request->getPost('nama'),
            'sekolah' => $this->request->getPost('sekolah'),
            'status' => $this->request->getPost('status')
        ];

        if ($validation->run($data, 'siswa') == FALSE) {
            session()->setFlashdata('inputs', $this->request->getPost());
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to('/siswa/create');
        } else {
            $simpan = $this->siswaModel->insertSiswa($data);
            if ($simpan) {
                session()->setFlashdata('success', 'Siswa Berhasil Ditambah');
                return redirect()->to('/siswa');
            }
        }
    }

    public function edit($id)
    {
        $data['siswa'] = $this->siswaModel->getSiswaById($id);
        echo view('siswa/form', $data);
    }

    public function update()
    {
        $id = $this->request->getPost('id_siswa');
        $validation = \Config\Services::validation();
        $data = [
            'nama' => $this->request->getPost('nama'),
            'sekolah' => $this->request->getPost('sekolah'),
            'status' => $this->request->getPost('status')
        ];


        if ($validation->run($data, 'siswa') == FALSE) {
            session()->setFlashdata('inputs', $this->request->getPost());
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to('/siswa/edit/' . $id);
        } else {
            $ubah = $this->siswaModel->updateSiswa($id, $data);
            if ($ubah) {
                session()->setFlashdata('info', 'Siswa Berhasil Diubah');
                return redirect()->to('/siswa');
            }
        }
    }

    public function delete($id)
    {
        $hapus = $this->siswaModel->deleteSiswa($id);
        if ($hapus) {
            session()->setFlashdata('warning', 'berhasil menghapus data');
            return redirect()->to('/siswa');
        } else {
            session()->setFlashdata('error', 'gagal menghapus data');
            return redirect()->to('/siswa');
        }
    }

    public function absensi($id_siswa)
    {
        $siswa = $this->siswaModel->getSiswa($id_siswa);
        $absensi = $this->absenModel->getAbsenBySiswa($id_siswa);
        $summary = $this->absenModel->getAbsenSummary($id_siswa); 

        $data = [
            'siswa' => $siswa,
            'absensi' => $absensi,
            'summary' => $summary 
        ];

        return view('siswa/absensi', $data);
    }
}
