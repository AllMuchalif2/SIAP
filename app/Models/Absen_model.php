<?php

namespace App\Models;

use CodeIgniter\Model;

class Absen_model extends Model
{
    protected $table = 'absen';
    protected $primaryKey = 'id_absen';
    protected $allowedFields = ['id_siswa', 'tanggal', 'keterangan', 'waktu'];

    public function insertAbsensi($data)
    {
        return $this->insert($data);
    }

    public function absen($id_siswa, $tanggal)
    {
        return $this->set('keterangan', 'Hadir')
            ->set('waktu', date('H:i:s'))
            ->where(['id_siswa' => $id_siswa, 'tanggal' => $tanggal])
            ->update();
    }
    public function getAbsensiByDate($tanggal)
    {
        return $this->select('absen.id_siswa, siswa.nama, siswa.sekolah, absen.waktu')
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

    /**
     * Ambil semua absensi untuk tanggal tertentu.
     */
    public function getByTanggal($tanggal)
    {
        return $this->select('absensi.*, siswa.nama, siswa.sekolah')
            ->join('siswa', 'siswa.id = absensi.id_siswa')
            ->where('tanggal', $tanggal)
            ->orderBy('siswa.nama', 'asc')
            ->findAll();
    }

    /**
     * Hitung jumlah per keterangan untuk tanggal tertentu.
     */
    public function countKeterangan($tanggal)
    {
        return $this->select('keterangan, COUNT(*) as jumlah')
            ->where('tanggal', $tanggal)
            ->groupBy('keterangan')
            ->get()
            ->getResultArray();
    }


}
