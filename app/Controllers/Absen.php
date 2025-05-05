<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\Absen_model;
use App\Models\Siswa_model;
use App\Models\User_model;

class Absen extends Controller
{
    protected $absensiModel;
    protected $siswaModel;
    protected $userModel;
    protected $session;


    public function __construct()
    {
        $this->absensiModel = new Absen_model();
        $this->siswaModel = new Siswa_model();
        $this->userModel = new User_model();
        $this->session = session();
    }

    public function index()
    {
        return view('absen/input');
    }
    public function absensi()
    {
        $id_siswa = $this->request->getPost('id_siswa');
        $tanggal = date('Y-m-d');

        $siswa = $this->siswaModel->find($id_siswa);
        if (!$siswa) {
            session()->setFlashdata('error', 'Siswa tidak ditemukan.<br>Periksa kembali id user');
            return redirect()->to('/');
        }
        if ($siswa['status'] !== 'active') {
            session()->setFlashdata('error', esc($siswa['nama']) . '<br> tidak dapat melakukan absensi karena sudah selesai prakerin.');
            return redirect()->to('/');
        }

        $absensi = $this->absensiModel->where(['id_siswa' => $id_siswa, 'tanggal' => $tanggal])->first();
        if ($absensi && $absensi['keterangan'] === 'Alpa') {
            $this->absensiModel->absen($id_siswa, $tanggal);
            session()->setFlashdata('success', 'Absensi berhasil <br><b> ' . esc($siswa['nama']) . '</b>');
        } else {
            session()->setFlashdata('error', esc($siswa['nama']) . '<br> sudah absen untuk hari ini.');
        }

        return redirect()->to('/');
    }


    // untuk dashboard
    public function dashboard()
    {
        $tanggal = date('Y-m-d');
        $count_hadir = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('keterangan', 'Hadir')
            ->countAllResults();

        $count_izin = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('keterangan', 'Izin')
            ->countAllResults();

        $count_sakit = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('keterangan', 'Sakit')
            ->countAllResults();

        $count_alpa = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('keterangan', 'Alpa')
            ->countAllResults();

        $absensi = $this->absensiModel->getAbsensiByDate($tanggal);
        return view('absen/dashboard', [
            'absensi' => $absensi,
            'count_hadir' => $count_hadir,
            'count_izin' => $count_izin,
            'count_sakit' => $count_sakit,
            'count_alpa' => $count_alpa
        ]);
    }

    public function input()
    {
        $siswaAktif = $this->siswaModel->where('status', 'active')->findAll();

        $tanggal = date('Y-m-d');
        $successCount = 0;
        $errorCount = 0;

        foreach ($siswaAktif as $siswa) {
            $absensiHariIni = $this->absensiModel
                ->where('id_siswa', $siswa['id_siswa'])
                ->where('tanggal', $tanggal)
                ->first();

            if (!$absensiHariIni) {
                $data = [
                    'id_siswa' => $siswa['id_siswa'],
                    'tanggal' => $tanggal,
                    'keterangan' => 'Alpa', // Default status
                    'waktu' => null
                ];

                try {
                    $this->absensiModel->insert($data);
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    log_message('error', 'Failed to insert attendance: ' . $e->getMessage());
                }
            }
        }

        $message = "Berhasil menginput absensi untuk $successCount siswa";
        if ($errorCount > 0) {
            $message .= " (Gagal: $errorCount)";
        }

        session()->setFlashdata('success', $message);
        return redirect()->to('/dashboard');
    }

    public function kehadiran()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $count_hadir = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('keterangan', 'Hadir')
            ->countAllResults();

        $count_izin = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('keterangan', 'Izin')
            ->countAllResults();

        $count_sakit = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('keterangan', 'Sakit')
            ->countAllResults();

        $count_alpa = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('keterangan', 'Alpa')
            ->countAllResults();

        $kehadiran = $this->absensiModel
            ->select('absen.id_siswa, siswa.nama, siswa.sekolah, absen.waktu , absen.keterangan')
            ->join('siswa', 'siswa.id_siswa = absen.id_siswa')
            ->where('absen.tanggal', $tanggal)
            ->findAll();

        return view('absen/index', [
            'kehadiran' => $kehadiran,
            'count_hadir' => $count_hadir,
            'count_izin' => $count_izin,
            'count_sakit' => $count_sakit,
            'count_alpa' => $count_alpa
        ]);
    }

    public function update()
    {
        $idSiswa = $this->request->getPost('id');
        $keterangan = $this->request->getPost('keterangan');
        $tanggal = $this->request->getPost('tanggal');
        $waktu = ($keterangan === 'Hadir') ? date('H:i:s') : null;

        $this->absensiModel->where('id_siswa', $idSiswa)
            ->where('tanggal', $tanggal)
            ->set([
                'keterangan' => $keterangan,
                'waktu' => $waktu
            ])
            ->update();
        return redirect()->back()->with('success', 'Keterangan berhasil diupdate.');
    }
}
