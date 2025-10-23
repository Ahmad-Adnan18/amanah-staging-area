<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 9pt; }
        table { width: 100%; border-collapse: collapse; }
        @page { size: A4 portrait; margin: 12mm; }
        h1.main-title {
            font-size: 18pt;
            margin-bottom: 2px;
            font-weight: bold;
            text-align: center;
        }
        .header-sub { font-size: 12pt; font-weight: bold; text-align: center; }
        .day-grid { margin-top: 15px; table-layout: fixed; }
        .day-grid td.day-column {
            width: 33.33%;
            vertical-align: top;
            padding: 0 5px;
        }
        .day-grid tr:first-child td { padding-bottom: 15px; }
        .day-title { text-align: center; font-weight: bold; font-size: 11pt; text-transform: uppercase; margin-bottom: 8px; }
        .slot-table { width: 100%; margin-bottom: 4px; }
        .slot-table td { border: none; padding: 1.5px 0; font-size: 8pt; border-bottom: 1px dotted #999; }
        .slot-num { width: 18px; color: #555; }
        .slot-subject { font-weight: bold; }
        .slot-detail { text-align: right; color: #555; white-space: nowrap; }
        .summary-table { margin-top: 20px; font-size: 7pt; }
        .summary-table th, .summary-table td { border: 1px solid #000; padding: 3px; text-align: center; }
        .signature-block { margin-top: 25px; width: 40%; float: right; text-align: center; font-size: 9pt; }
        .logo-placeholder {
            display: inline-block;
            height: 25pt;
            width: auto;
            background-color: #f0f0f0;
            text-align: center;
            line-height: 18pt;
            color: #aaa;
            font-size: 13pt;
            vertical-align: middle;
            margin-right: 5px;
        }
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
        .logo-image {
            height: 25pt;
            width: auto;
            max-width: 100pt;
            vertical-align: middle;
        }
        .signature-image {
            width: 150px;
            height: 60px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 15px;">
        @php
            $logoJadwalPelajaran = \App\Models\AppSetting::getValue('logo_jadwal_pelajaran', 'images/logo-jadwal-pelajaran.png');
            $logoJadwalMengajar = \App\Models\AppSetting::getValue('logo_jadwal_mengajar', 'images/logo-mengajar.png');
            
            // Convert logos to base64 for PDF
            $logoPelajaranBase64 = null;
            $logoMengajarBase64 = null;
            
            if ($logoJadwalPelajaran && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoJadwalPelajaran)) {
                $logoContent = \Illuminate\Support\Facades\Storage::disk('public')->get($logoJadwalPelajaran);
                $logoMime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($logoJadwalPelajaran);
                $logoPelajaranBase64 = 'data:' . $logoMime . ';base64,' . base64_encode($logoContent);
            }
            
            if ($logoJadwalMengajar && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoJadwalMengajar)) {
                $logoContent = \Illuminate\Support\Facades\Storage::disk('public')->get($logoJadwalMengajar);
                $logoMime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($logoJadwalMengajar);
                $logoMengajarBase64 = 'data:' . $logoMime . ';base64,' . base64_encode($logoContent);
            }
        @endphp

        @if($type == 'kelas')
            @if($logoPelajaranBase64)
                <img src="{{ $logoPelajaranBase64 }}" class="logo-image" alt="Logo Jadwal Pelajaran"/>
            @else
                <div class="logo-placeholder">LOGO JADWAL PELAJARAN</div>
            @endif
            <div class="header-sub">{{ $title }}</div>
        @else
            @if($logoMengajarBase64)
                <img src="{{ $logoMengajarBase64 }}" class="logo-image" alt="Logo Jadwal Mengajar"/>
            @else
                <div class="logo-placeholder">LOGO JADWAL MENGAJAR</div>
            @endif
            <div class="header-sub">Al Ustadz/ah: {{ $teacherName }}</div>
        @endif
    </div>

    <table class="day-grid">
        <tr>
            @foreach($days as $dayKey => $dayName)
                @if($loop->iteration > 3) @continue @endif
                <td class="day-column">
                    <div class="day-title">{{ $dayName }}</div>
                    @foreach($timeSlots as $timeSlot)
                        <table class="slot-table">
                            <tr>
                                <td class="slot-num">{{ $timeSlot }}.</td>
                                @if(isset($schedules[$dayKey][$timeSlot]))
                                    @php $schedule = $schedules[$dayKey][$timeSlot][0]; @endphp
                                    <td class="slot-subject">{{ $schedule->subject->nama_pelajaran ?? '' }}</td>
                                    <td class="slot-detail">
                                        @if($type == 'kelas')
                                            Ruang: {{ $schedule->room->name ?? '-' }}
                                        @else
                                            {{ $schedule->kelas->nama_kelas ?? '' }}
                                        @endif
                                    </td>
                                @else
                                    <td></td><td></td>
                                @endif
                            </tr>
                        </table>
                    @endforeach
                </td>
            @endforeach
        </tr>
        <tr>
            @foreach($days as $dayKey => $dayName)
                @if($loop->iteration <= 3) @continue @endif
                <td class="day-column">
                    <div class="day-title">{{ $dayName }}</div>
                    @foreach($timeSlots as $timeSlot)
                        <table class="slot-table">
                            <tr>
                                <td class="slot-num">{{ $timeSlot }}.</td>
                                @if(isset($schedules[$dayKey][$timeSlot]))
                                    @php $schedule = $schedules[$dayKey][$timeSlot][0]; @endphp
                                    <td class="slot-subject">{{ $schedule->subject->nama_pelajaran ?? '' }}</td>
                                    <td class="slot-detail">
                                        @if($type == 'kelas')
                                            Ruang: {{ $schedule->room->name ?? '-' }}
                                        @else
                                            {{ $schedule->kelas->nama_kelas ?? '' }}
                                        @endif
                                    </td>
                                @else
                                    <td></td><td></td>
                                @endif
                            </tr>
                        </table>
                    @endforeach
                </td>
            @endforeach
        </tr>
    </table>

    @if($type == 'guru')
        <table class="summary-table">
            <thead>
                <tr><th colspan="8">REKAPITULASI JAM MENGAJAR</th></tr>
                <tr>
                    <th>HARI</th><th>SABTU</th><th>AHAD</th><th>SENIN</th>
                    <th>SELASA</th><th>RABU</th><th>KAMIS</th><th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>JUMLAH JAM</td>
                    <td>{{ $teachingHoursSummary['sabtu'] }}</td><td>{{ $teachingHoursSummary['ahad'] }}</td>
                    <td>{{ $teachingHoursSummary['senin'] }}</td><td>{{ $teachingHoursSummary['selasa'] }}</td>
                    <td>{{ $teachingHoursSummary['rabu'] }}</td><td>{{ $teachingHoursSummary['kamis'] }}</td>
                    <td style="font-weight: bold;">{{ $teachingHoursSummary['total'] }}</td>
                </tr>
            </tbody>
        </table>

        @php
            $logoTtdJadwal = \App\Models\AppSetting::getValue('logo_tanda_tangan_jadwal', 'images/logo-tanda-tangan-jadwal.jpg');
            $namaPenandatanganJadwal = \App\Models\AppSetting::getValue('nama_penandatangan_jadwal', 'Al Ustadz Ahmad Fauzi, M.Pd.');
            $jabatanPenandatanganJadwal = \App\Models\AppSetting::getValue('jabatan_penandatangan_jadwal', 'Kepala Sekolah');
            $lokasiPondok = \App\Models\AppSetting::getValue('alamat_pondok', 'Ciekek Hilir');
            $namaPondok = \App\Models\AppSetting::getValue('nama_pondok', 'Pondok Pesantren Kun Karima');
            
            // Convert signature to base64 for PDF
            $signatureJadwalBase64 = null;
            if ($logoTtdJadwal && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoTtdJadwal)) {
                $signatureContent = \Illuminate\Support\Facades\Storage::disk('public')->get($logoTtdJadwal);
                $signatureMime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($logoTtdJadwal);
                $signatureJadwalBase64 = 'data:' . $signatureMime . ';base64,' . base64_encode($signatureContent);
            }
        @endphp

        <div class="signature-block">
            <p>{{ explode(',', $lokasiPondok)[0] }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>An. {{ $namaPondok }}</p>
            <p>{{ $jabatanPenandatanganJadwal }}</p>
            
            @if($signatureJadwalBase64)
                <img src="{{ $signatureJadwalBase64 }}" class="signature-image" alt="Logo Tanda Tangan Jadwal"/>
            @else
                <div class="signature-placeholder">TANDA TANGAN JADWAL</div>
            @endif
            
            <p style="font-weight: bold; text-decoration: underline;">{{ $namaPenandatanganJadwal }}</p>
        </div>
    @endif
</body>
</html>