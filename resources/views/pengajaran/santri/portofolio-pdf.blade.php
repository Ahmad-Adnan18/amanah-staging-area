<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio Santri - {{ $santri->nama }}</title>
    <style>
        @page {
            margin: 20px;
            size: A4;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #dc2626;
            margin: 0;
            font-size: 20px;
        }

        .header h2 {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }

        .profile-section {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #f9fafb;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 15px;
        }

        .profile-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #dc2626;
        }

        .profile-details {
            flex: 1;
        }

        .profile-details h3 {
            margin: 0 0 5px 0;
            color: #1f2937;
            font-size: 16px;
        }

        .profile-details p {
            margin: 2px 0;
            color: #6b7280;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 25px;
        }

        .stat-card {
            padding: 8px;
            text-align: center;
            border-radius: 3px;
            background: white;
            border: 1px solid #e5e7eb;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 9px;
            color: #6b7280;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #dc2626;
            color: white;
            padding: 8px 12px;
            margin: 0 0 12px 0;
            font-size: 14px;
            font-weight: bold;
            border-radius: 4px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table th {
            background: #f3f4f6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #d1d5db;
            font-size: 11px;
        }

        .table td {
            padding: 8px;
            border: 1px solid #d1d5db;
            font-size: 11px;
        }

        .table tr:nth-child(even) {
            background: #f9fafb;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-aktif {
            background: #fef3c7;
            color: #92400e;
        }

        .status-selesai {
            background: #d1fae5;
            color: #065f46;
        }

        .status-sembuh {
            background: #d1fae5;
            color: #065f46;
        }

        .status-kronis {
            background: #fef3c7;
            color: #92400e;
        }

        .poin-badge {
            background: #d1fae5;
            color: #065f46;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 10px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-style: italic;
        }

        .page-break {
            page-break-before: always;
        }

    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>PORTOFOLIO SANTRI</h1>
        <h2>Pondok Pesantren Kun Karima - {{ date('d F Y') }}</h2>
    </div>

    <!-- Profil Santri -->
    <div class="profile-section">
        <div class="profile-info">
            <img class="profile-photo" src="{{ $santri->foto ? storage_path('app/public/' . $santri->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($santri->nama) . '&background=dc2626&color=fff&size=80' }}" alt="Foto Santri">
            <div class="profile-details">
                <h3>{{ $santri->nama }}</h3>
                <p><strong>NIS:</strong> {{ $santri->nis }}</p>
                <p><strong>Kelas:</strong> {{ $santri->kelas->nama_kelas ?? 'N/A' }}</p>
                <p><strong>Rayon:</strong> {{ $santri->rayon }}</p>
                <p><strong>Jenis Kelamin:</strong> {{ $santri->jenis_kelamin }}</p>
            </div>
        </div>
    </div>

    <!-- Statistik Cepat -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $santri->perizinans->count() }}</div>
            <div class="stat-label">Total Izin</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $santri->pelanggarans->count() }}</div>
            <div class="stat-label">Pelanggaran</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $santri->prestasis->count() }}</div>
            <div class="stat-label">Prestasi</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $santri->riwayatPenyakits->count() }}</div>
            <div class="stat-label">Riwayat Penyakit</div>
        </div>
    </div>

    <!-- Perizinan -->
    <div class="section">
        <div class="section-title">RIWAYAT PERIZINAN</div>
        @if($santri->perizinans->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Jenis Izin</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($santri->perizinans->sortByDesc('created_at') as $izin)
                <tr>
                    <td>{{ $izin->jenis_izin }}</td>
                    <td>
                        {{ $izin->tanggal_mulai->format('d M Y') }}
                        @if($izin->tanggal_akhir && $izin->tanggal_akhir != $izin->tanggal_mulai)
                        - {{ $izin->tanggal_akhir->format('d M Y') }}
                        @endif
                    </td>
                    <td>{{ Str::limit($izin->keterangan, 50) }}</td>
                    <td>
                        <span class="status-badge {{ $izin->status == 'aktif' ? 'status-aktif' : 'status-selesai' }}">
                            {{ ucfirst($izin->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">Tidak ada data perizinan</div>
        @endif
    </div>

    <!-- Pelanggaran -->
    <div class="section">
        <div class="section-title">RIWAYAT PELANGGARAN</div>
        @if($santri->pelanggarans->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Jenis Pelanggaran</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($santri->pelanggarans->sortByDesc('tanggal_kejadian') as $pelanggaran)
                <tr>
                    <td>{{ $pelanggaran->jenis_pelanggaran }}</td>
                    <td>{{ $pelanggaran->tanggal_kejadian->format('d M Y') }}</td>
                    <td>{{ $pelanggaran->keterangan }}</td>
                    <td>{{ $pelanggaran->dicatat_oleh }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">Tidak ada data pelanggaran</div>
        @endif
    </div>

    <!-- Prestasi -->
    <div class="section">
        <div class="section-title">PRESTASI</div>
        @if($santri->prestasis->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Prestasi</th>
                    <th>Tanggal</th>
                    <th>Poin</th>
                    <th>Keterangan</th>
                    <th>Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($santri->prestasis->sortByDesc('tanggal') as $prestasi)
                <tr>
                    <td>{{ $prestasi->nama_prestasi }}</td>
                    <td>{{ $prestasi->tanggal->format('d M Y') }}</td>
                    <td>
                        <span class="poin-badge">+{{ $prestasi->poin }}</span>
                    </td>
                    <td>{{ $prestasi->keterangan }}</td>
                    <td>{{ $prestasi->pencatat->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">Tidak ada data prestasi</div>
        @endif
    </div>

    <!-- Riwayat Penyakit -->
    <div class="section">
        <div class="section-title">RIWAYAT PENYAKIT</div>
        @if($santri->riwayatPenyakits->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Penyakit</th>
                    <th>Tanggal Diagnosis</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($santri->riwayatPenyakits->sortByDesc('tanggal_diagnosis') as $penyakit)
                <tr>
                    <td>{{ $penyakit->nama_penyakit }}</td>
                    <td>{{ $penyakit->tanggal_diagnosis->format('d M Y') }}</td>
                    <td>
                        <span class="status-badge {{ $penyakit->status == 'sembuh' ? 'status-sembuh' : ($penyakit->status == 'kronis' ? 'status-kronis' : 'status-aktif') }}">
                            {{ ucfirst($penyakit->status) }}
                        </span>
                    </td>
                    <td>{{ Str::limit($penyakit->keterangan, 50) }}</td>
                    <td>{{ $penyakit->pencatat->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">Tidak ada data riwayat penyakit</div>
        @endif
    </div>

    <!-- Catatan Harian -->
    <div class="section">
        <div class="section-title">CATATAN HARIAN</div>
        @if($santri->catatanHarians->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Catatan</th>
                    <th>Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($santri->catatanHarians->sortByDesc('tanggal') as $catatan)
                <tr>
                    <td>{{ $catatan->tanggal->format('d M Y') }}</td>
                    <td>{{ $catatan->catatan }}</td>
                    <td>{{ $catatan->pencatat->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">Tidak ada data catatan harian</div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis pada {{ date('d F Y H:i:s') }}</p>
        <p>Portofolio Santri - {{ $santri->nama }} ({{ $santri->nis }})</p>
    </div>
</body>
</html>
