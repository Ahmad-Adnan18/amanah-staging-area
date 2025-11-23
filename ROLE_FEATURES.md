# Proposal Penguatan Role & Fitur Sistem Perizinan Santri

## 1. Pendahuluan
Dokumen ini merangkum modul, alur kontrol akses, dan catatan operasional Sistem Perizinan Santri dalam format proposal agar mudah disosialisasikan ke pimpinan, unit pengajaran, serta tim pendukung lainnya.

## 2. Tujuan
- Menjelaskan cakupan fitur inti yang sudah tersedia di platform.
- Menguraikan keterkaitan modul dengan peran pengguna untuk memastikan pemisahan tugas yang jelas.
- Menyediakan referensi singkat terhadap komponen teknis (controller/policy) bagi tim pengembang.

## 3. Ruang Lingkup Modul

| Area Modul | Dampak Bisnis | Komponen Teknis |
| --- | --- | --- |
| Dashboard & Jadwal | Tampilan ringkas sesuai role, jadwal publik, cetak jadwal guru/kelas/mapel, "Jadwal Saya" untuk guru | `DashboardController`, `PublicScheduleController` |
| Data Santri & Akademik | CRUD santri/kelas/mapel/jabatan, penempatan kelas, kode wali, input nilai/leger, laporan akademik | `SantriPolicy`, `KelasPolicy`, `MataPelajaranPolicy`, `NilaiPolicy` |
| Absensi & Rekapan | Ledger harian, laporan periodik, ekspor absensi dan rekapan kegiatan dengan granular access control | Routes `pengajaran.absensi.*`, `pengajaran.rekapan-harian.*`, policy terkait |
| Perizinan & Pelanggaran | Pengajuan izin, approval, riwayat & PDF, pencatatan pelanggaran, bulk delete | `PerizinanController`, `PerizinanPolicy`, `PelanggaranController`, `PelanggaranPolicy` |
| Keasramaan & Prestasi | Pencatatan catatan harian serta prestasi oleh wali kelas/admin | `CatatanHarianController`, `PrestasiController`, `CatatanHarianPolicy`, `PrestasiPolicy` |
| Kesehatan | Riwayat penyakit santri dapat dicatat/diperbarui tim kesehatan/pengasuhan/admin | `RiwayatPenyakitController`, `RiwayatPenyakitPolicy` |
| Ubudiyah/Tahfidz | Input setoran, laporan mutaba'ah, API santri-kelas, master data surat | `TahfidzController`, `SetoranTahfidz` CRUD, `SuratController` |
| Penjadwalan | Konfigurasi aturan, ketersediaan guru, generator otomatis/hybrid, manual grid, tukar jadwal, fix rooms | `ScheduleGeneratorController`, `ScheduleManualController`, `ScheduleSwapController` |
| Master Data & Inventaris | Manajemen user/guru/ruang, inventory per ruang, app settings, teacher availability | Admin controllers, `InventoryItemController` (role admin/pengajaran) |
| Konten & Slider | Pengelolaan slider hero: CRUD, upload file/video/URL, urutan, aktivasi | `SliderController` |
| Laporan & Ekspor | Laporan perizinan/pelanggaran/santri, ekspor rapor & portofolio tanpa batasan role tambahan | `ReportController`, `SantriProfileController` |
| Portal Wali Santri | Registrasi wali, dashboard khusus, akses profil/rapor/portofolio anak, navigasi mobile | `WaliRegistrationController`, tampilan `wali.dashboard` |

## 4. Rincian Deliverables per Modul
1. **Dashboard & Jadwal** – menyediakan insight real-time sesuai role, akses jadwal publik, serta template cetak untuk guru, kelas, dan mata pelajaran.
2. **Data Santri & Akademik** – memastikan data pokok santri dan struktur akademik tersinkron, termasuk generator kode wali dan penjurnalan nilai.
3. **Absensi & Rekapan** – mendukung pencatatan lintas hari, ekspor bukti resmi, dan laporan aktivitas harian santri.
4. **Perizinan & Pelanggaran** – memfasilitasi proses izin santri end-to-end serta pencatatan pelanggaran dengan kemampuan bulk action.
5. **Keasramaan & Prestasi** – menampung catatan perkembangan non-akademik oleh wali kelas maupun admin keasramaan.
6. **Kesehatan** – menjaga histori kesehatan santri untuk koordinasi pengasuhan dan keputusan izin.
7. **Ubudiyah/Tahfidz** – mengarsipkan setoran tahfidz, progres mutaba'ah, serta pengelolaan daftar surat.
8. **Penjadwalan** – menghadirkan kombinasi generator otomatis dan manual grid untuk menyusun jadwal belajar, termasuk fitur tukar jadwal.
9. **Master Data & Inventaris** – memusatkan kontrol atas akun, guru, ruangan, inventaris, serta pengaturan aplikasi.
10. **Konten & Slider** – mempermudah tim dokumentasi merilis materi visual di portal.
11. **Laporan & Ekspor** – menyediakan kolom bukti tertulis untuk pimpinan dan wali.
12. **Portal Wali Santri** – menjadi garda depan komunikasi kepada orang tua.

## 5. Matriks Akses Per Role

| Role | Fokus Tugas | Hak Akses Utama |
| --- | --- | --- |
| **Admin** | Super user & konfigurasi | Kuasa penuh via `before()` di hampir semua policy; manajemen user/guru/ruang/surat/inventory/app settings; seluruh modul penjadwalan; CRUD perizinan, pelanggaran, santri, kelas, absensi, rekapan, kesehatan, slider, tahfidz, laporan, ekspor. |
| **Pengajaran** | Akademik & jadwal | CRUD santri/kelas/mapel/jabatan/nilai; akses penuh penjadwalan; membuat absensi & rekapan; membuat pelanggaran; melihat laporan, data santri, jadwal publik; menghapus absensi; kelola prestasi/catatan jika menjabat wali kelas. |
| **Pengasuhan** | Pembinaan asrama | Lihat data santri/kelas/laporan/absensi; membuat & mengelola perizinan serta pelanggaran; akses rekapan harian (view/report); melihat riwayat penyakit; dapat mengisi prestasi/catatan bila ditugaskan wali kelas. |
| **Kesehatan** | Layanan kesehatan | CRUD riwayat penyakit; akses perizinan terkait kesehatan; ikut absensi dan rekapan (dengan pembatasan edit); melihat laporan/data santri/jadwal; baca pelanggaran. |
| **Ustadz_Umum** | Pengajar umum | Akses baca data santri/kelas/laporan; input absensi & rekapan (edit dibatasi pada jadwal sendiri); melihat jadwal publik; tanpa hak CRUD master data. |
| **Teacher** | Akun guru terdaftar | Mirip `ustadz_umum` dengan batasan edit absensi hanya untuk jadwal milik sendiri; dashboard menampilkan jadwal harian dan halaman "Jadwal Saya". |
| **Dokumentasi** | Publikasi & media | Mengelola slider hero (CRUD + upload + ordering); lihat rekapan/laporan; akses baca data santri/kelas sebagai referensi konten. |
| **Ubudiyah** | Tahfidz & ibadah | Kelola setoran tahfidz (input, hapus), laporan mutaba'ah, dan master data surat; akses data kelas/santri untuk kebutuhan tahfidz. |
| **Wali_Santri** | Orang tua/wali | Dashboard khusus menampilkan santri asuhan, status izin/pelanggaran; akses profil, rapor, portofolio, dan ekspor PDF; diperbolehkan memperbarui data anak sesuai `SantriPolicy`. |
| **Wali Kelas (Jabatan)** | Status jabatan | Pengguna dengan jabatan wali kelas (via `JabatanUser`) bisa mencatat Catatan Harian & Prestasi untuk kelasnya; edit/hapus dibatasi pada pencatat aslinya. |

## 6. Catatan Implementasi
- **Visibilitas lintas role** — Perizinan dan pelanggaran dapat dibaca semua role untuk memastikan koordinasi cepat antar unit.
- **Laporan universal** — `ReportController` dan ekspor portofolio tidak memakai middleware tambahan, sehingga setiap staf dapat mengunduh data resmi tanpa eskalasi.
- **Inventaris terbatas** — `InventoryItemController` menyertakan pengecekan manual agar hanya admin dan pengajaran yang bisa memodifikasi aset ruangan.
- **Onboarding wali** — Rute `/wali-register` otomatis menetapkan role `wali_santri`, mempermudah strategi sosialisasi ke orang tua baru.
