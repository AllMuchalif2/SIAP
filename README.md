## 🚀 Instalasi Cepat

```bash
git clone https://github.com/AllMuchalif2/SIAP
```

```bash
cd SIAP
```

```bash
composer install
```

1. Import file `.sql` ke database dengan nama `db_aku_absen` atau jalankan `php spark migrate` dan `php spark db:seed`
2. Konfigurasi database di `.env`
3. Jalankan server:

```bash
php spark serve
```

---

## 📌 Daftar Routing

---

### 🌐 Umum (Tanpa Login)

| Route            | Fungsi                                                     |
| ---------------- | ---------------------------------------------------------- |
| `/`              | Halaman input absensi                                      |
| `/absen/absen`   | Menyimpan input absen                                      |
| `/dashboard`     | Melihat absensi hari ini (admin juga bisa input dari sini) |
| `/login`         | Halaman login                                              |
| `/attempt-login` | Proses login                                               |
| `/logout`        | Logout                                                     |

---

### 👤 Setelah Login sebagai **Asisten**

| Route                 | Fungsi                                          |
| --------------------- | ----------------------------------------------- |
| `/siswa`              | Data siswa + persentase absensi + tombol detail |
| `/siswa/absensi/{id}` | Lihat detail hari & tanggal absensi siswa       |
| `/user/show/{id}`     | Lihat detail akun & ganti password              |

---

### 🛠️ Setelah Login sebagai **Admin**

#### 👩‍🎓 Manajemen Data Siswa

| Route                 | Fungsi                                       |
| --------------------- | -------------------------------------------- |
| `/siswa`              | Data siswa + persentase + aksi edit & delete |
| `/siswa/absensi/{id}` | Detail absensi siswa                         |
| `/siswa/create`       | Form tambah siswa                            |
| `/siswa/edit/{id}`    | Form edit siswa                              |
| `/siswa/save`         | Simpan data siswa baru                       |
| `/siswa/update`       | Update data siswa                            |
| `/siswa/update/{id}`  | Update siswa berdasarkan ID                  |
| `/siswa/delete/{id}`  | Hapus siswa                                  |

#### 📅 Manajemen Absensi

| Route                  | Fungsi                                                    |
| ---------------------- | --------------------------------------------------------- |
| `/absensi`             | Data absensi dengan filter (tanggal & status hadir/alpa)  |
| `/riwayat`             | Riwayat update absensi                                    |
| `/absensi/update`      | Ubah keterangan absensi                                   |
| `/absensi/update/{id}` | Ubah keterangan berdasarkan ID                            |
| `/input`               | Tandai semua siswa sebagai alpa (input manual oleh admin) |

#### 👥 Manajemen Pengguna

| Route                | Fungsi                           |
| -------------------- | -------------------------------- |
| `/user`              | Daftar user                      |
| `/user/show/{id}`    | Detail akun user                 |
| `/user/create`       | Form tambah user                 |
| `/user/save`         | Simpan user baru                 |
| `/user/edit/{id}`    | Form edit user                   |
| `/user/update`       | Update data user                 |
| `/user/update/{id}`  | Update user berdasarkan ID       |
| `/user/delete/{id}`  | Hapus user                       |
| `/user/reset/{id}`   | Reset password user (oleh admin) |
| `/user/newpass/{id}` | Ganti password sendiri           |

---

### ⚙️ Konfigurasi IP Address untuk Absensi

| Route                 | Fungsi                       |
| --------------------- | ---------------------------- |
| `/konfigurasi`        | Dashboard konfigurasi sistem |
| `/konfigurasi/update` | Update data konfigurasi      |

---
