# Database Schema - Sistem Perizinan Santri

## Overview
Database schema untuk Sistem Perizinan Santri yang dibangun dengan Laravel. Sistem ini mengelola data santri, perizinan, jadwal pelajaran, nilai, dan berbagai aspek pendidikan lainnya.

## Tables Structure

### 1. `users`
Tabel untuk menyimpan data pengguna sistem.
```sql
- id (bigint, primary, auto_increment)
- name (string)
- email (string, unique)
- profile_photo (string, nullable)
- email_verified_at (timestamp, nullable)
- password (string, hashed)
- role (string, 50) - contoh: admin, guru, wali_santri
- teacher_code (string, nullable, unique)
- remember_token (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

**Relasi:**
- HasOne → `santri` (jika role = wali_santri)
- HasMany → `jabatans`
- HasMany → `schedules`
- HasMany → `unavailabilities`
- HasOne → `teacher`

---

### 2. `santris`
Tabel untuk menyimpan data santri/pondok.
```sql
- id (bigint, primary, auto_increment)
- wali_id (bigint, nullable, foreign key ke users.id)
- kode_registrasi_wali (string, nullable, unique)
- nis (string, nullable, unique)
- nama (string)
- jenis_kelamin (enum: 'Putra', 'Putri')
- tempat_lahir (string, nullable)
- agama (string, nullable)
- alamat (text, nullable)
- no_telepon (string, nullable)
- email (string, nullable)
- asal_sekolah (string, nullable)
- nama_ayah (string, nullable)
- nama_ibu (string, nullable)
- tanggal_lahir (date, nullable)
- rayon (string, nullable)
- kelas_id (bigint, foreign key ke kelas.id)
- foto (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

**Relasi:**
- BelongsTo → `kelas`
- HasMany → `perizinans`
- HasMany → `pelanggarans`
- HasMany → `nilai`
- HasMany → `catatanHarians`
- HasMany → `prestasis`
- HasMany → `absensis`
- HasMany → `riwayatPenyakits`

---

### 3. `kelas`
Tabel untuk menyimpan data kelas/tingkatan.
```sql
- id (bigint, primary, auto_increment)
- nama_kelas (string, unique)
- tingkatan (string, nullable)
- total_jp_sehari (integer, default: 7)
- is_active_for_scheduling (boolean, default: true)
- level (integer, nullable) - contoh: 7, 8, 9
- parallel_name (string, nullable) - contoh: A, B, Putra A
- kurikulum_template_id (bigint, nullable, foreign key ke kurikulum_templates.id)
- room_id (bigint, nullable, foreign key ke rooms.id)
- created_at (timestamp)
- updated_at (timestamp)
```

---

### 4. `perizinans`
Tabel untuk menyimpan data perizinan santri.
```sql
- id (bigint, primary, auto_increment)
- santri_id (bigint, foreign key ke santris.id)
- jenis_izin (string)
- kategori (string)
- keterangan (text)
- tanggal_mulai (date)
- tanggal_akhir (date, nullable)
- status (enum: 'aktif', 'selesai', 'terlambat', default: 'aktif')
- created_by (bigint, foreign key ke users.id)
- updated_by (bigint, nullable, foreign key ke users.id)
- created_at (timestamp)
- updated_at (timestamp)
- deleted_at (timestamp, nullable - soft deletes)
```

---

### 5. `mata_pelajarans`
Tabel untuk menyimpan data mata pelajaran.
```sql
- id (bigint, primary, auto_increment)
- nama_pelajaran (string)
- tingkatan (string, default: 'Umum')
- duration_jp (integer) - Durasi dalam Jam Pelajaran
- requires_special_room (boolean, default: false)
- kategori (enum: 'Umum', 'Diniyah')
- created_at (timestamp)
- updated_at (timestamp)
```

---

### 6. `teachers`
Tabel untuk menyimpan data guru/pengajar.
```sql
- id (bigint, primary, auto_increment)
- name (string)
- teacher_code (string, nullable, unique)
- user_id (bigint, nullable, foreign key ke users.id)
- created_at (timestamp)
- updated_at (timestamp)
```

---

### 7. `schedules`
Tabel untuk menyimpan data jadwal pelajaran.
```sql
- id (bigint, primary, auto_increment)
- kelas_id (bigint, foreign key ke kelas.id)
- mata_pelajaran_id (bigint, foreign key ke mata_pelajarans.id)
- teacher_id (bigint, foreign key ke teachers.id)
- room_id (bigint, foreign key ke rooms.id)
- day_of_week (tinyint) - 0-6 (Minggu-Sabtu)
- time_slot (tinyint) - Slot waktu
- created_at (timestamp)
- updated_at (timestamp)
```

---

### 8. `rooms`
Tabel untuk menyimpan data ruangan.
```sql
- id (bigint, primary, auto_increment)
- name (string)
- type (enum: 'Biasa', 'Khusus', default: 'Biasa')
- jumlah_meja (integer, default: 0)
- jumlah_kursi (integer, default: 0)
- created_at (timestamp)
- updated_at (timestamp)
```

---

### 9. `jabatans`
Tabel untuk menyimpan data jabatanstruktuur.
```sql
- id (bigint, primary, auto_increment)
- nama_jabatan (string, unique)
- created_at (timestamp)
- updated_at (timestamp)
```

---

### 10. `jabatan_user`
Tabel pivot untuk relasi many-to-many antara users dan jabatans.
```sql
- id (bigint, primary, auto_increment)
- user_id (bigint, foreign key ke users.id)
- jabatan_id (bigint, foreign key ke jabatans.id)
- created_at (timestamp)
- updated_at (timestamp)
```

---

### 11. `setoran_tahfidz`
Tabel untuk menyimpan data setoran hafalan Al-Qur'an.
```sql
- id (bigint, primary, auto_increment)
- santri_id (bigint, foreign key ke santris.id, cascade delete)
- kelas_id (bigint, foreign key ke kelas.id, cascade delete)
- teacher_id (bigint, foreign key ke teachers.id, cascade delete)
- surat_id (bigint, foreign key ke surats.id, cascade delete)
- tanggal_setor (date)
- jenis_setoran (enum: 'baru', 'murojaah')
- ayat_mulai (integer)
- ayat_selesai (integer)
- nilai (enum: 'mumtaz', 'jayyid_jiddan', 'jayyid', 'maqbul')
- keterangan (text, nullable)
- created_by (bigint, nullable, foreign key ke users.id)
- updated_by (bigint, nullable, foreign key ke users.id)
- created_at (timestamp)
- updated_at (timestamp)
```

---

### Other Supporting Tables

#### `nilais`
Tabel untuk menyimpan data nilai akademik santri.
```sql
- id (bigint, primary, auto_increment)
- santri_id (bigint, foreign key ke santris.id)
- mata_pelajaran_id (bigint, foreign key ke mata_pelajarans.id)
- kelas_id (bigint, foreign key ke kelas.id)
- nilai (integer/double)
- semester (string)
- tahun_ajaran (string)
- created_at (timestamp)
- updated_at (timestamp)
```

#### `absensis`
Tabel untuk menyimpan data kehadiran santri.
```sql
- id (bigint, primary, auto_increment)
- santri_id (bigint, foreign key ke santris.id)
- kelas_id (bigint, foreign key ke kelas.id)
- tanggal (date)
- status (enum: 'hadir', 'sakit', 'izin', 'alpa')
- keterangan (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### `prestasis`
Tabel untuk menyimpan data prestasi santri.
```sql
- id (bigint, primary, auto_increment)
- santri_id (bigint, foreign key ke santris.id)
- jenis_prestasi (string)
- tingkat (string) - contoh: sekolah, kabupaten, provinsi
- tahun (integer)
- keterangan (text)
- created_at (timestamp)
- updated_at (timestamp)
```

#### `pelanggarans`
Tabel untuk menyimpan data pelanggaran santri.
```sql
- id (bigint, primary, auto_increment)
- santri_id (bigint, foreign key ke santris.id)
- jenis_pelanggaran (string)
- tingkat_pelanggaran (string) - contoh: ringan, sedang, berat
- tanggal (date)
- keterangan (text)
- created_at (timestamp)
- updated_at (timestamp)
```

#### `catatan_harians`
Tabel untuk menyimpan catatan harian santri.
```sql
- id (bigint, primary, auto_increment)
- santri_id (bigint, foreign key ke santris.id)
- tanggal (date)
- catatan (text)
- created_by (bigint, foreign key ke users.id)
- created_at (timestamp)
- updated_at (timestamp)
```

#### `riwayat_penyakits`
Tabel untuk menyimpan data riwayat penyakit santri.
```sql
- id (bigint, primary, auto_increment)
- santri_id (bigint, foreign key ke santris.id)
- nama_penyakit (string)
- tanggal_mulai (date)
- tanggal_selesai (date, nullable)
- keterangan (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### `surats`
Tabel untuk menyimpan data surat Al-Qur'an.
```sql
- id (bigint, primary, auto_increment)
- nama_surat (string)
- jumlah_ayat (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

#### `kurikulum_templates`
Tabel untuk menyimpan template kurikulum.
```sql
- id (bigint, primary, auto_increment)
- nama_template (string)
- kelas_id (bigint, foreign key ke kelas.id)
- created_at (timestamp)
- updated_at (timestamp)
```

#### System Tables
Tables yang digunakan untuk keperluan sistem Laravel:
- `migrations` - Log migrasi database
- `password_resets` - Reset password
- `failed_jobs` - Queue yang gagal
- `jobs` - Queue antrian
- `cache` - Data cache
- `cache_locks` - Lock cache
- `job_batches` - Batch job
- `sessions` - Sesi pengguna
- `slider_items` - Item slider UI
- `teacher_unavailabilities` - Ketersediaan guru
- `schedule_substitutions` - Substitusi jadwal
- `inventory_items` - Inventaris barang
- `hour_priorities` - Prioritas jam
- `app_settings` - Pengaturan aplikasi
- `blocked_times` - Waktu terblokir
- `kelas_mata_pelajaran` - Pivot kelas-mata pelajaran
- `mata_pelajaran_teacher` - Pivot mata pelajaran-guru
- `mata_pelajaran_user` - Pivot mata pelajaran-user
- `rekapan_harian` - Rekap harian

## Key Relationships

### User Relationships
- User (wali_santri) → Santri (1:1)
- User → JabatanUser (1:N)
- User (guru) → Schedule (1:N)
- User → Teacher (1:1)

### Santri Relationships
- Santri → Kelas (N:1)
- Santri → Perizinan (1:N)
- Santri → Nilai (1:N)
- Santri → Absensi (1:N)
- Santri → Prestasi (1:N)
- Santri → Pelanggaran (1:N)
- Santri → CatatanHarian (1:N)
- Santri → RiwayatPenyakit (1:N)

### Schedule Relationships
- Schedule → Kelas (N:1)
- Schedule → MataPelajaran (N:1)
- Schedule → Teacher (N:1)
- Schedule → Room (N:1)

## Notes
1. Semua tabel utama memiliki `created_at` dan `updated_at` timestamps
2. Tabel `perizinans` menggunakan soft deletes dengan `deleted_at`
3. Beberapa tabel menggunakan cascade delete untuk menjaga integritas data
4. Sistem menggunakan foreign key constraints untuk menjaga konsistensi data
5. Role user yang umum: admin, guru, wali_santri

## ERD Summary
```
Users (1) ←→ (N) Santri
Users (1) ←→ (N) JabatanUser ←→ (N) Jabatans
Users (1) ←→ (1) Teacher
Santris (N) → (1) Kelas
Santris (1) → (N) Perizinans, Nilai, Absensi, Prestasi, Pelanggaran, CatatanHarian, RiwayatPenyakit
Kelas (1) → (N) Schedules
Schedule → Teacher, MataPelajaran, Room (N:1)
Santris (1) → (N) SetoranTahfidz → Surat (N:1)
```
