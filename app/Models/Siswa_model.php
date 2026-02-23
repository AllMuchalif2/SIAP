<?php

namespace App\Models;

use CodeIgniter\Model;

class Siswa_model extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    protected $fillable = ['id_siswa', 'nama', 'sekolah', 'status'];
    protected $allowedFields = ['id_siswa', 'nama', 'sekolah', 'status', 'deleted_at'];

    // Soft Deletes
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

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
                $siswa['total_absensi'] = $this->getTotalAbsensi($siswa['id_siswa']);
                $siswa['absen_count'] = $this->getAbsenCount($siswa['id_siswa']);
            }
            return $siswaList;
        } else {
            $siswa = $this->getWhere(['id_siswa' => $id])->getRowArray();
            if ($siswa) {
                $siswa['attendance_percentage'] = $this->getPersenHadir($id);
                $siswa['total_absensi'] = $this->getTotalAbsensi($id);
                $siswa['absen_count'] = $this->getAbsenCount($id);
            }
            return $siswa;
        }
    }

    public function getTotalAbsensi($id_siswa)
    {
        $builder = $this->db->table('absen');
        $builder->select('COUNT(*) as total_absensi');
        $builder->where('id_siswa', $id_siswa);
        $builder->where('deleted_at IS NULL');
        $result = $builder->get()->getRow();

        return $result ? $result->total_absensi : 0;
    }

    /**
     * Cek apakah siswa memiliki data absen (aktif / belum soft delete)
     */
    public function hasAbsen($id_siswa): bool
    {
        return $this->getAbsenCount($id_siswa) > 0;
    }

    /**
     * Hitung jumlah absen aktif milik siswa
     */
    public function getAbsenCount($id_siswa): int
    {
        $result = $this->db->table('absen')
            ->where('id_siswa', $id_siswa)
            ->where('deleted_at IS NULL')
            ->countAllResults();

        return (int) $result;
    }

    public function getPersenHadir($id_siswa)
    {
        $builder = $this->db->table('absen');
        $builder->select('SUM(CASE WHEN keterangan = "Hadir" THEN 1 ELSE 0 END) as hadir');
        $builder->select('SUM(CASE WHEN keterangan = "Sakit" THEN 1 ELSE 0 END) as sakit');
        $builder->select('SUM(CASE WHEN keterangan = "Izin" THEN 1 ELSE 0 END) as izin');
        $builder->select('SUM(CASE WHEN keterangan = "Alpa" THEN 1 ELSE 0 END) as alpa');
        $builder->where('id_siswa', $id_siswa);
        $builder->where('deleted_at IS NULL');
        $result = $builder->get()->getRow();

        if ($result) {
            $totalHadir = $result->hadir + $result->sakit + $result->izin;
            $totalRecords = $result->hadir + $result->sakit + $result->izin + $result->alpa;

            if ($totalRecords > 0) {
                return ($totalHadir / $totalRecords) * 100;
            }
        }

        return 0;
    }

    public function insertSiswa($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function updateSiswa($id, $data)
    {
        return $this->db->table($this->table)->update($data, ['id_siswa' => $id]);
    }

    /**
     * Soft delete siswa (deleted_at diisi timestamp)
     */
    public function deleteSiswa($id)
    {
        return $this->delete($id);
    }
}
