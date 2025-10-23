<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Izin Santri</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 9pt; }
        @page { size: A4 portrait; margin: 12mm; }
        
        h1.main-title {
            font-size: 18pt;
            margin-bottom: 2px;
            font-weight: bold;
            text-align: center;
        }
        .header-sub { font-size: 12pt; font-weight: bold; text-align: center; margin-bottom: 15px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { border: 1px solid #000; padding: 3px; text-align: left; font-size: 8pt; }
        table th { font-weight: bold; background-color: #f0f0f0; }
        
        .signature-block { margin-top: 25px; width: 40%; float: right; text-align: center; font-size: 9pt; }
        
        .logo-image {
            height: 60pt;
            width: auto;
            max-width: 200pt;
        }
        
        .signature-image {
            width: 150px;
            height: 60px;
            object-fit: contain;
        }
        
        p { margin: 5px 0; line-height: 1.2; }
        hr { border: 0; border-top: 1px solid #000; margin: 10px 0; }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 15px;">
        @php
            $logoSuratIzin = \App\Models\AppSetting::getValue('logo_surat_izin');
            $namaPondok = \App\Models\AppSetting::getValue('nama_pondok', 'Pondok Pesantren Kun Karima');
            $alamatPondok = \App\Models\AppSetting::getValue('alamat_pondok', 'Ciekek Hilir, Kec. Taktakan, Kota Serang, Banten');
            
            // Convert logo to base64 for PDF
            $logoBase64 = null;
            if ($logoSuratIzin && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoSuratIzin)) {
                $logoContent = \Illuminate\Support\Facades\Storage::disk('public')->get($logoSuratIzin);
                $logoMime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($logoSuratIzin);
                $logoBase64 = 'data:' . $logoMime . ';base64,' . base64_encode($logoContent);
            }
        @endphp
        
        <div style="display: inline-block; vertical-align: middle; margin-right: 10px;">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" class="logo-image" alt="Logo Surat Izin"/>
            @else
                <div style="height: 80pt; width: 80pt; background: #f0f0f0; text-align: center; line-height: 80pt; color: #aaa; border: 1px solid #ccc;">
                    LOGO
                </div>
            @endif
        </div>
        
        <div style="display: inline-block; vertical-align: middle; text-align: center;">
            <h1 class="main-title">SURAT IZIN SANTRI</h1>
            <div class="header-sub">{{ $namaPondok }}</div>
            @if($alamatPondok)
            <p style="font-size: 10pt; margin-top: 5px; font-style: italic;">{{ $alamatPondok }}</p>
            @endif
        </div>
        <hr>
    </div>

    <p>Nomor: {{ $perizinan->id }}/IZIN/{{ now()->format('Y') }}</p>
    <p>Kepada Yth.</p>
    <p>Orang Tua/Wali Santri {{ $perizinan->santri->nama }}</p>
    <br>

    <p>Dengan hormat,</p>
    <p>Bersama ini kami informasikan bahwa santri atas nama:</p>

    <table>
        <tr><th>Nama Santri</th><td>{{ $perizinan->santri->nama }}</td></tr>
        <tr><th>NIS</th><td>{{ $perizinan->santri->nis }}</td></tr>
        <tr><th>Kelas</th><td>{{ $perizinan->santri->kelas->nama_kelas ?? 'N/A' }}</td></tr>
        <tr><th>Jenis Izin</th><td>{{ $perizinan->jenis_izin }}</td></tr>
        <tr><th>Kategori</th><td>{{ ucwords($perizinan->kategori) }}</td></tr>
        <tr><th>Keterangan/Diagnosa</th><td>{{ $perizinan->keterangan }}</td></tr>
        <tr><th>Tanggal Mulai</th><td>{{ $perizinan->tanggal_mulai->format('d F Y') }}</td></tr>
        <tr><th>Tanggal Kembali</th><td>{{ $perizinan->tanggal_akhir ? $perizinan->tanggal_akhir->format('d F Y') : 'Tidak ditentukan' }}</td></tr>
    </table>

    <p>Demikian surat izin ini dibuat untuk dipergunakan sebagaimana mestinya.</p>

    <div class="signature-block">
        @php
            $logoTtdSuratIzin = \App\Models\AppSetting::getValue('logo_tanda_tangan_surat_izin');
            $namaPenandatanganSuratIzin = \App\Models\AppSetting::getValue('nama_penandatangan_surat_izin', 'Al Ustadz Muhammad Iqbal, Lc.');
            $jabatanPenandatanganSuratIzin = \App\Models\AppSetting::getValue('jabatan_penandatangan_surat_izin', 'Bagian Pengasuhan Santri');
            
            // Convert signature to base64 for PDF
            $signatureBase64 = null;
            if ($logoTtdSuratIzin && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoTtdSuratIzin)) {
                $signatureContent = \Illuminate\Support\Facades\Storage::disk('public')->get($logoTtdSuratIzin);
                $signatureMime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($logoTtdSuratIzin);
                $signatureBase64 = 'data:' . $signatureMime . ';base64,' . base64_encode($signatureContent);
            }
        @endphp

        <p>Ciekek Hilir, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>An. {{ $namaPondok }}</p>
        <p>{{ $jabatanPenandatanganSuratIzin }}</p>
        
        @if($signatureBase64)
            <img src="{{ $signatureBase64 }}" class="signature-image" alt="Logo Tanda Tangan Surat Izin"/>
        @else
            <div style="width: 150px; height: 60px; background-color: #f9f9f9; text-align: center; line-height: 60px; color: #aaa; font-size: 8pt; margin: 0 auto;">
                TANDA TANGAN SURAT IZIN
            </div>
        @endif
        
        <p style="font-weight: bold; text-decoration: underline;">{{ $namaPenandatanganSuratIzin }}</p>
    </div>
</body>
</html>