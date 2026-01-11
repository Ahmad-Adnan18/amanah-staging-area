# PANDUAN REGISTRASI AKUN GURU

Dokumen ini menjelaskan langkah-langkah bagi guru untuk mendaftarkan akun ke dalam sistem dan menghubungkannya dengan data penjadwalan (jadwal, mata pelajaran, dll).

## 1. Persiapan Data (Untuk Admin)
Sebelum guru bisa mendaftar, Admin harus memastikan dua hal:
1.  **Kode Registrasi Aplikasi** sudah diatur.
    *   Cek file `.env`, pastikan variabel `GURU_REGISTRATION_CODE` terisi.
    *   Contoh: `GURU_REGISTRATION_CODE=RAHASIA123`
2.  **Kode Identitas Guru** sudah di-generate/diisi.
    *   Buka menu **Manajemen Guru**.
    *   Pastikan guru yang bersangkutan memiliki Kode Guru (misal: `KK001`).
    *   Jika belum punya, gunakan tombol **Generate Kode** di halaman tersebut.
    *   **Export Data Guru** ke Excel dan bagikan Kode Guru tersebut kepada masing-masing guru.

## 2. Langkah-langkah Pendaftaran (Untuk Guru)

### Langkah 1: Akses Halaman Pendaftaran
1. Buka halaman Login aplikasi.
2. Klik tombol **"Buat Akun Guru"**.

### Langkah 2: Lengkapi Data Diri
Isi formulir dengan data yang valid:
*   **Nama Lengkap**: Sesuai nama asli.
*   **Email**: Email aktif yang Anda gunakan.
*   **Password**: Minimal 8 karakter.
*   **Konfirmasi Password**: Ulangi password.

### Langkah 3: Masukkan Kode Validasi
Sistem menggunakan keamanan ganda (Double Validation):

1.  **Kode Registrasi Aplikasi**:
    *   Minta kode ini kepada Admin IT / TU.
    *   Kode ini sama untuk semua guru (sebagai pintu gerbang keamanan).
2.  **Kode Identitas Guru**:
    *   Minta kode unik Anda kepada Admin (misal: `KK015`).
    *   Kode ini digunakan untuk mengenali siapa Anda di sistem dan otomatis menghubungkan akun Anda dengan jadwal mengajar Anda.

### Langkah 4: Selesai
Klik tombol **Daftar Akun**.
*   Jika sukses, Anda akan langsung masuk ke Dashboard.
*   Role Anda otomatis diatur sebagai **"Ustadz Umum"**.
*   Data Anda (Jadwal, Mapel) otomatis terhubung.

## Troubleshooting
*   **Error "Kode Registrasi Aplikasi tidak valid"**: Anda salah memasukkan kode rahasia aplikasi. Tanya Admin.
*   **Error "Kode guru ini sudah terdaftar dengan akun lain"**: Artinya kode identitas guru Anda (`KK...`) sudah dipakai oleh orang lain untuk mendaftar. Hubungi Admin untuk reset data jika perlu.
*   **Error "The selected teacher code is invalid"**: Kode guru yang Anda masukkan tidak ditemukan di database. Pastikan penulisan benar (huruf besar/kecil berpengaruh).
