<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        @page { 
            size: A4 portrait; 
            margin: 15mm; 
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .title { 
            font-size: 16pt; 
            font-weight: bold; 
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14pt;
            margin-bottom: 10px;
        }
        
        .table th, .table td { 
            border: 1px solid #000; 
            padding: 8px; 
            text-align: left;
        }
        .table th { 
            background-color: #f0f0f0; 
            font-weight: bold;
            text-align: center;
        }
        .table td { 
            text-align: left;
        }
        
        .no { width: 50px; text-align: center; }
        .nama { width: 60%; }
        .keterangan { width: 40%; }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10pt;
        }
        .signature-block { margin-top: 25px; width: 40%; float: right; text-align: center; font-size: 9pt; }
        .signature-placeholder {
            display: inline-block;
            width: 150px;
            height: 60px;
            background-color: #f9f9f9;
            text-align: center;
            line-height: 60px;
            color: #aaa;
            font-size: 8pt;
        }
        .signature-image {
            width: 150px;
            height: 60px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">DAFTAR GURU LIBUR</div>
        <div class="subtitle">Hari: {{ $hari }}</div>    
    </div>

    <table class="table">
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="nama">Nama Guru</th>
                <th class="keterangan">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guruLibur as $index => $guru)
            <tr>
                <td class="no">{{ $index + 1 }}</td>
                <td class="nama">{{ $guru['name'] }}</td>
                <td class="keterangan">{{ $guru['reason'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center;">Tidak ada guru yang libur pada hari ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        @php
            $logoTtdGuruLibur = \App\Models\AppSetting::getValue('logo_tanda_tangan_guru_libur', 'images/logo-tanda-tangan-guru-libur.jpg');
            $namaPenandatanganGuruLibur = \App\Models\AppSetting::getValue('nama_penandatangan_guru_libur', 'Al Ustadzah Siti Aisyah, S.Ag.');
            $jabatanPenandatanganGuruLibur = \App\Models\AppSetting::getValue('jabatan_penandatangan_guru_libur', 'Wakil Direktur Pengasuhan');
            
            // Convert signature to base64 for PDF
            $signatureGuruLiburBase64 = null;
            if ($logoTtdGuruLibur && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoTtdGuruLibur)) {
                $signatureContent = \Illuminate\Support\Facades\Storage::disk('public')->get($logoTtdGuruLibur);
                $signatureMime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($logoTtdGuruLibur);
                $signatureGuruLiburBase64 = 'data:' . $signatureMime . ';base64,' . base64_encode($signatureContent);
            }
        @endphp

        <div class="signature-block">
            <p>Ciekek Hilir, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>An. Pemimpin Pondok Pesantren Kun Karima</p>
            <p>{{ $jabatanPenandatanganGuruLibur }}</p>
            
            @if($signatureGuruLiburBase64)
                <img src="{{ $signatureGuruLiburBase64 }}" class="signature-image" alt="Logo Tanda Tangan Guru Libur"/>
            @else
                <div class="signature-placeholder">TANDA TANGAN GURU LIBUR</div>
            @endif
            
            <p style="font-weight: bold; text-decoration: underline;">{{ $namaPenandatanganGuruLibur }}</p>
        </div>
    </div>
</body>
</html>