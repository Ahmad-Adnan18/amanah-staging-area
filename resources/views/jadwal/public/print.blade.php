<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        /* [DIUBAH] Ukuran font dan margin diperkecil agar muat 3 kolom di A4 Portrait */
        body { font-family: 'Times New Roman', Times, serif; font-size: 9pt; }
        table { width: 100%; border-collapse: collapse; }
        @page { size: A4 portrait; margin: 12mm; } /* Tetap Portrait, margin diperkecil */

        /* [DIUBAH] .header-image dihapus, diganti styling untuk H1 */
        h1.main-title {
            font-size: 18pt;
            margin-bottom: 2px;
            font-weight: bold;
            text-align: center;
        }
        .header-sub { font-size: 13pt; font-weight: bold; text-align: center; }

        /* Layout 3 Kolom dengan 2 Baris */
        .day-grid { margin-top: 15px; table-layout: fixed; }
        .day-grid td.day-column {
            width: 33.33%;
            vertical-align: top;
            padding: 0 5px;
        }
        .day-grid tr:first-child td { padding-bottom: 15px; } /* Jarak antar baris hari */
        
        .day-title { text-align: center; font-weight: bold; font-size: 11pt; text-transform: uppercase; margin-bottom: 8px; }
        .slot-table { width: 100%; margin-bottom: 4px; }
        .slot-table td { border: none; padding: 1.5px 0; font-size: 8pt; border-bottom: 1px dotted #999; }
        .slot-num { width: 18px; color: #555; }
        .slot-subject { font-weight: bold; }
        .slot-detail { text-align: right; color: #555; white-space: nowrap; }

        .summary-table { margin-top: 20px; font-size: 7pt; }
        .summary-table th, .summary-table td { border: 1px solid #000; padding: 3px; text-align: center; }
        .signature-block { margin-top: 25px; width: 40%; float: right; text-align: center; font-size: 9pt; }

    </style>
</head>
<body>

@if($type == 'kelas')
    <div style="text-align: center; margin-bottom: 15px;">
        {{-- [DIUBAH] Gambar diganti dengan Teks H1 --}}
       {{-- <h1 class="main-title">Jadwal Pelajaran</h1> --}}
        <div class="header-sub">{{ $title }}</div>
    </div>
@else
    <div style="text-align: center; margin-bottom: 15px;">
        {{-- [DIUBAH] Gambar diganti dengan Teks H1 --}}
        <h1 class="main-title">Jadwal Mengajar</h1>
        <div class="header-sub">Al Ustadz/ah: {{ $teacherName }}</div>
    </div>
@endif

<!-- Layout diubah menjadi tabel 3x2 (3 kolom, 2 baris) -->
<table class="day-grid">
    <tr>
        {{-- Baris Pertama: Sabtu, Ahad, Senin --}}
        @foreach($days as $dayKey => $dayName)
            @if($loop->iteration > 3) @break @endif
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
        {{-- Baris Kedua: Selasa, Rabu, Kamis --}}
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


{{-- Tampilkan Rekapitulasi & TTD hanya untuk Guru --}}
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

    <div class="signature-block">
        <p>Ciekek Hilir, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>An. Pemimpin Pondok Pesantren Kun Karima</p>
        <p>Direktur II</p>
        <div style="height: 50px;"></div>
        <p style="font-weight: bold; text-decoration: underline;">Al Ustadz Dzikri Adzkiya Arief, B.A.</p>
    </div>
@endif

</body>
</html>

