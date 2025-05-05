<?php

namespace App\Models;

use CodeIgniter\Model;

class Siswa_model extends Model
{

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    protected $fillable = ['nama', 'sekolah', 'status'];

    public function getSiswaById($id)
    {
        return $this->where(['id_siswa' => $id])->first();
    }

    public function getSiswa($id = false)
    {
        if ($id === false) {
            $siswaList = $this->findAll();
            foreach ($siswaList as &$siswa) {
                $siswa['attendance_percentage'] = $this->getPersenHadir($siswa['id_siswa']);
                $siswa['total_absensi'] = $this->getTotalAbsensi($siswa['id_siswa']); // Menambahkan total absensi
            }
            return $siswaList;
        } else {
            $siswa = $this->getWhere(['id_siswa' => $id])->getRowArray();
            if ($siswa) {
                $siswa['attendance_percentage'] = $this->getPersenHadir($id);
                $siswa['total_absensi'] = $this->getTotalAbsensi($id); // Menambahkan total absensi
            }
            return $siswa;
        }
    }

    public function getTotalAbsensi($id_siswa)
    {
        $builder = $this->db->table('absen');
        $builder->select('COUNT(*) as total_absensi');
        $builder->where('id_siswa', $id_siswa);
        $result = $builder->get()->getRow();

        return $result ? $result->total_absensi : 0; // Mengembalikan 0 jika tidak ada catatan
    }

    public function getPersenHadir($id_siswa)
    {
        $builder = $this->db->table('absen');
        $builder->select('SUM(CASE WHEN keterangan = "Hadir" THEN 1 ELSE 0 END) as hadir');
        $builder->select('SUM(CASE WHEN keterangan = "Sakit" THEN 1 ELSE 0 END) as sakit');
        $builder->select('SUM(CASE WHEN keterangan = "Izin" THEN 1 ELSE 0 END) as izin');
        $builder->select('SUM(CASE WHEN keterangan = "Alpa" THEN 1 ELSE 0 END) as alpa');
        $builder->where('id_siswa', $id_siswa);
        $result = $builder->get()->getRow();


        if ($result) {
            $totalHadir = $result->hadir + $result->sakit + $result->izin;
            $totalRecords = $result->hadir + $result->sakit + $result->izin + $result->alpa;

            if ($totalRecords > 0) {
                return ($totalHadir / $totalRecords) * 100; // Menghitung persentase kehadiran
            }
        }

        return 0; // Kembalikan 0 jika tidak ada catatan ditemukan atau totalRecords adalah 0
    }

    public function insertSiswa($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function updateSiswa($id, $data)
    {
        return $this->db->table($this->table)->update($data, ['id_siswa' => $id]);
    }

    public function deleteSiswa($id)
    {
        return $this->db->table($this->table)->delete(['id_siswa' => $id]);
    }
}
