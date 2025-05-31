<?php

namespace App\Models;

use CodeIgniter\Model;

class Absen_model extends Model
{
    protected $table = 'absen';
    protected $primaryKey = 'id_absen';
    protected $allowedFields = ['id_siswa', 'tanggal', 'keterangan', 'waktu', 'waktu_pulang'];
    protected $useTimestamps = false;

    public function insertAbsensi($data)
    {
        return $this->insert($data);
    }

    public function absenMasuk($id_siswa, $tanggal)
    {
        // Cek apakah sudah ada record untuk hari ini
        $absenHariIni = $this->where(['id_siswa' => $id_siswa, 'tanggal' => $tanggal])->first();
        
        if (!$absenHariIni) {
            // Jika belum ada, buat record baru
            return $this->insert([
                'id_siswa' => $id_siswa,
                'tanggal' => $tanggal,
                'keterangan' => 'Hadir',
                'waktu' => date('H:i:s')
            ]);
        }
        
        return false;
    }

    public function absenPulang($id_siswa, $tanggal)
    {
        // Cek apakah sudah ada absen masuk tapi belum pulang
        $absenHariIni = $this->where('id_siswa', $id_siswa)
                            ->where('tanggal', $tanggal)
                            ->where('keterangan', 'Hadir')
                            ->where('waktu_pulang IS NULL')
                            ->first();

        if ($absenHariIni) {
            // Update waktu pulang
            return $this->update($absenHariIni['id_absen'], [
                'waktu_pulang' => date('H:i:s')
            ]);
        }

        return false;
    }

    public function getAbsensiByDate($tanggal)
    {
        return $this->select('absen.id_siswa, siswa.nama, siswa.sekolah, absen.waktu, absen.waktu_pulang')
            ->join('siswa', 'siswa.id_siswa = absen.id_siswa')
            ->where('absen.tanggal', $tanggal)
            ->where('absen.keterangan', 'Hadir')
            ->orderBy('absen.waktu', 'ASC')
            ->findAll();
    }

    public function getAbsenBySiswa($id_siswa)
    {
        return $this->where('id_siswa', $id_siswa)
            ->orderBy('tanggal', 'DESC')
            ->findAll();
    }

    public function getAbsenSummary($id_siswa)
    {
        $builder = $this->db->table('absen');
        $builder->select('COUNT(*) as total_records');
        $builder->select('SUM(CASE WHEN keterangan = "Hadir" THEN 1 ELSE 0 END) as hadir');
        $builder->select('SUM(CASE WHEN keterangan = "Sakit" THEN 1 ELSE 0 END) as sakit');
        $builder->select('SUM(CASE WHEN keterangan = "Izin" THEN 1 ELSE 0 END) as izin');
        $builder->select('SUM(CASE WHEN keterangan = "Alpa" THEN 1 ELSE 0 END) as alpa');
        $builder->where('id_siswa', $id_siswa);
        return $builder->get()->getRow();
    }

    public function updateKeterangan($idSiswa, $tanggal, $data)
    {
        return $this->where('id_siswa', $idSiswa)
            ->where('tanggal', $tanggal)
            ->set($data)
            ->update();
    }

    public function getByTanggal($tanggal)
    {
        return $this->select('absen.*, siswa.nama, siswa.sekolah')
            ->join('siswa', 'siswa.id_siswa = absen.id_siswa')
            ->where('tanggal', $tanggal)
            ->orderBy('siswa.nama', 'asc')
            ->findAll();
    }

    public function countKeterangan($tanggal)
    {
        return $this->select('keterangan, COUNT(*) as jumlah')
            ->where('tanggal', $tanggal)
            ->groupBy('keterangan')
            ->get()
            ->getResultArray();
    }

    // Menghitung durasi kerja (opsional)
    public function getDurasiKerja($id_siswa, $tanggal)
    {
        $absen = $this->where('id_siswa', $id_siswa)
                     ->where('tanggal', $tanggal)
                     ->first();
        
        if ($absen && $absen['waktu'] && $absen['waktu_pulang']) {
            $masuk = strtotime($absen['waktu']);
            $pulang = strtotime($absen['waktu_pulang']);
            $diff = $pulang - $masuk;
            
            $jam = floor($diff / (60 * 60));
            $menit = floor(($diff - ($jam * 60 * 60)) / 60);
            
            return $jam . ' jam ' . $menit . ' menit';
        }
        
        return '-';
    }
}