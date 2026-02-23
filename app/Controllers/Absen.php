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
    protected $db;

    public function __construct()
    {
        $this->absensiModel = new Absen_model();
        $this->siswaModel = new Siswa_model();
        $this->userModel = new User_model();
        $this->session = session();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tanggal = date('Y-m-d');

        $absensi = $this->absensiModel
            ->select('absen.id_siswa, siswa.nama, siswa.sekolah, absen.waktu, absen.waktu_pulang, absen.keterangan')
            ->join('siswa', 'siswa.id_siswa = absen.id_siswa')
            ->where('absen.tanggal', $tanggal)
            ->where('absen.keterangan', 'Hadir')
            ->orderBy('absen.waktu', 'ASC')
            ->findAll();

        return view('absen/input', ['absensi' => $absensi]);
    }

    public function absensi()
    {
        $id_siswa = $this->request->getPost('id_siswa');
        $tanggal = date('Y-m-d');
        $waktu_sekarang = date('H:i:s');

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

        if (!$absensi) {
            // Jika belum ada record, buat baru dengan status Hadir
            $data = [
                'id_siswa' => $id_siswa,
                'tanggal' => $tanggal,
                'keterangan' => 'Hadir',
                'waktu' => $waktu_sekarang
            ];
            $this->absensiModel->insert($data);
            session()->setFlashdata('success', 'Absensi masuk berhasil <br><b>' . esc($siswa['nama']) . '</b>');
        } elseif ($absensi['keterangan'] === 'Alpa') {
            // Update dari Alpa ke Hadir
            $this->absensiModel->absen($id_siswa, $tanggal);
            session()->setFlashdata('success', 'Absensi berhasil <br><b>' . esc($siswa['nama']) . '</b>');
        } elseif ($absensi['keterangan'] === 'Hadir' && is_null($absensi['waktu_pulang'])) {
            // Catat waktu pulang
            $this->absensiModel
                ->where('id_absen', $absensi['id_absen'])
                ->set('waktu_pulang', $waktu_sekarang)
                ->update();
            session()->setFlashdata('success', 'Absensi pulang berhasil <br><b>' . esc($siswa['nama']) . '</b>');
        } else {
            session()->setFlashdata('error', esc($siswa['nama']) . '<br> sudah melakukan absensi hari ini.');
        }

        return redirect()->to('/');
    }

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

        $absensi = $this->absensiModel
            ->select('absen.id_siswa, siswa.nama, siswa.sekolah, absen.waktu, absen.waktu_pulang')
            ->join('siswa', 'siswa.id_siswa = absen.id_siswa')
            ->where('absen.tanggal', $tanggal)
            ->where('absen.keterangan', 'Hadir')
            ->orderBy('absen.waktu', 'ASC')
            ->findAll();

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
                    'keterangan' => 'Alpa',
                    'waktu' => null,
                    'waktu_pulang' => null
                ];

                try {
                    $this->absensiModel->insert($data);
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    log_message('error', 'Gagal mengabsen: ' . $e->getMessage());
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
            ->select('absen.id_siswa, siswa.nama, siswa.sekolah, absen.waktu, absen.waktu_pulang, absen.keterangan')
            ->join('siswa', 'siswa.id_siswa = absen.id_siswa')
            ->where('absen.tanggal', $tanggal)
            ->orderBy('siswa.nama', 'ASC')
            ->findAll();

        return view('absen/index', [
            'kehadiran' => $kehadiran,
            'count_hadir' => $count_hadir,
            'count_izin' => $count_izin,
            'count_sakit' => $count_sakit,
            'count_alpa' => $count_alpa,
            'tanggal' => $tanggal
        ]);
    }

    public function update()
    {
        $idSiswa = $this->request->getPost('id');
        $keterangan = $this->request->getPost('keterangan');
        $tanggal = $this->request->getPost('tanggal');
        $waktu_masuk = $this->request->getPost('waktu_masuk');
        $waktu_pulang = $this->request->getPost('waktu_pulang');

        $absen = $this->db->table('absen')
            ->select('absen.*, siswa.nama')
            ->join('siswa', 'siswa.id_siswa = absen.id_siswa')
            ->where('absen.id_siswa', $idSiswa)
            ->where('absen.tanggal', $tanggal)
            ->get()
            ->getRowArray();

        $username = session()->get('username');

        if (!$absen) {
            return redirect()->back()->with('error', 'Data absensi tidak ditemukan.');
        }

        $dataUpdate = [
            'keterangan' => $keterangan,
            'waktu' => ($keterangan === 'Hadir') ? ($waktu_masuk ?: date('H:i:s')) : null,
            'waktu_pulang' => ($keterangan === 'Hadir') ? $waktu_pulang : null
        ];

        // Simpan update
        $this->absensiModel
            ->where('id_siswa', $idSiswa)
            ->where('tanggal', $tanggal)
            ->set($dataUpdate)
            ->update();

        // Simpan ke tabel log
        $this->db->table('absensi_log')->insert([
            'id_absen' => $absen['id_absen'],
            'id_siswa' => $absen['id_siswa'],
            'nama_siswa' => $absen['nama'],
            'keterangan_lama' => $absen['keterangan'],
            'keterangan_baru' => $keterangan,
            'waktu_lama' => $absen['waktu'],
            'waktu_baru' => $dataUpdate['waktu'],
            'waktu_pulang_lama' => $absen['waktu_pulang'] ?? null,
            'waktu_pulang_baru' => $dataUpdate['waktu_pulang'],
            'username' => $username,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Data absensi berhasil diupdate.');
    }

    public function riwayat()
    {
        $tanggal = $this->request->getGet('tanggal');
        $builder = $this->db->table('absensi_log');

        if ($tanggal) {
            $builder->where('DATE(updated_at)', $tanggal);
        }

        $log = $builder->orderBy('updated_at', 'DESC')->get()->getResultArray();

        return view('absen/riwayat', [
            'log' => $log,
            'tanggal' => $tanggal
        ]);
    }

    public function durasi()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        $absensi = $this->absensiModel
            ->select('absen.id_siswa, siswa.nama, absen.waktu, absen.waktu_pulang')
            ->join('siswa', 'siswa.id_siswa = absen.id_siswa')
            ->where('absen.tanggal', $tanggal)
            ->where('absen.keterangan', 'Hadir')
            ->where('absen.waktu_pulang IS NOT NULL')
            ->orderBy('siswa.nama', 'ASC')
            ->findAll();

        // Hitung durasi untuk setiap absensi
        foreach ($absensi as &$absen) {
            $masuk = strtotime($absen['waktu']);
            $pulang = strtotime($absen['waktu_pulang']);
            $diff = $pulang - $masuk;

            $jam = floor($diff / (60 * 60));
            $menit = floor(($diff - ($jam * 60 * 60)) / 60);

            $absen['durasi'] = $jam . ' jam ' . $menit . ' menit';
        }

        return view('absen/durasi', [
            'absensi' => $absensi,
            'tanggal' => $tanggal
        ]);
    }
}