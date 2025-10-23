<x-app-layout>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush

    <style>
        [x-cloak] {
            display: none !important;
        }

        .rekapan-select {
            width: 100% !important;
        }

        .keterangan-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.25;
            background-color: #f9fafb;
            transition: all 0.15s ease-in-out;
        }

        .keterangan-input:focus {
            outline: none;
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
            background-color: white;
        }

        .keterangan-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: center;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .rekapan-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 0.5rem;
            margin-bottom: 0;
        }

        .rekapan-grid-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .jam-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: center;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        @media (max-width: 767px) {
            .rekapan-grid {
                grid-template-columns: repeat(4, 1fr);
                /* 4 kolom di mobile: 3 jam + 1 keterangan per baris */
            }

            .jam-label,
            .keterangan-label {
                font-size: 0.65rem;
            }

            .rekapan-select,
            .keterangan-input {
                font-size: 0.8rem;
                padding: 0.4rem 0.5rem;
            }

            /* Responsive untuk tombol Lihat Laporan */
            .header-actions {
                flex-direction: column;
                gap: 0.5rem;
            }

            .lihat-laporan-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .rekapan-grid {
                grid-template-columns: repeat(2, 1fr);
                /* 2 kolom di very small mobile */
            }
        }

    </style>

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header & Filter (sama) -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex-1">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                            Rekapan Harian Santri
                        </h1>
                        <p class="mt-2 text-slate-600 text-sm sm:text-base">
                            Input rekapan kehadiran santri per hari
                        </p>
                    </div>

                    <!-- Tombol Lihat Laporan -->
                    <div class="header-actions flex items-center">
                        <a href="{{ route('pengajaran.rekapan-harian.laporan') }}" class="lihat-laporan-btn inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Filter Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-6 items-end">
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $selectedKelas == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Tampilkan Santri
                        </button>
                    </div>
                </form>
            </div>

            @if($santris->isNotEmpty())
            <form action="{{ route('pengajaran.rekapan-harian.store') }}" method="POST" id="rekapan-form">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $selectedKelas }}">
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                        <div class="flex flex-col sm:flex-row justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Input Rekapan Harian</h3>
                            <div class="mt-2 sm:mt-0 flex items-center space-x-4">
                                <div class="flex items-center space-x-2 text-sm">
                                    @foreach([0 => 'Alfa', 1 => 'Hadir', 2 => 'Sakit', 3 => 'Izin'] as $value => $label)
                                    <div class="flex items-center">
                                        <span class="inline-block w-3 h-3 rounded-full 
                                                    {{ $value == 0 ? 'bg-red-500' : 
                                                       ($value == 1 ? 'bg-green-500' : 
                                                       ($value == 2 ? 'bg-yellow-500' : 'bg-blue-500')) }} mr-1"></span>
                                        <span>{{ $label }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @if (auth()->user()->role === 'pengajaran' || auth()->user()->role === 'admin')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                    Simpan Rekapan
                                </button>
                                @else
                                <p class="text-red-500 font-medium">Anda tidak memiliki akses untuk menyimpan rekapan.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Grid Layout: 7 Jam + 1 Keterangan -->
                    <div class="divide-y divide-slate-200">
                        @foreach($santris as $index => $santri)
                        @php
                        $rekapan = $rekapanData[$santri->id] ?? null;
                        @endphp
                        <div class="p-4 hover:bg-slate-50">
                            <input type="hidden" name="rekapan[{{ $index }}][santri_id]" value="{{ $santri->id }}">

                            <!-- Nama Santri Header -->
                            <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-200">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $santri->nama }}</div>
                                    <div class="text-sm text-gray-500">NIS: {{ $santri->nis }}</div>
                                </div>
                                <!-- Optional: Total Hadir Indicator -->
                                <div class="text-xs text-slate-500">
                                    @php
                                    $hadirCount = 0;
                                    for($j=1; $j<=7; $j++) { $status=$rekapan ? ($rekapan->{"jam_$j"} ?? 1) : 1;
                                        if($status == 1) $hadirCount++;
                                        }
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $hadirCount }}/7 Hadir
                                        </span>
                                </div>
                            </div>

                            <!-- Rekapan Grid: 7 Jam + 1 Keterangan -->
                            <div class="rekapan-grid">
                                <!-- Jam 1-7 -->
                                @for($jam = 1; $jam <= 7; $jam++) @php $status=$rekapan ? ($rekapan->{"jam_$jam"} ?? 1) : 1;
                                    $class = match($status) {
                                    0 => 'bg-red-100 border-red-300 text-red-800',
                                    1 => 'bg-green-100 border-green-300 text-green-800',
                                    2 => 'bg-yellow-100 border-yellow-300 text-yellow-900',
                                    3 => 'bg-blue-100 border-blue-300 text-blue-800',
                                    default => 'bg-green-100 border-green-300 text-green-800'
                                    };
                                    @endphp
                                    <div class="rekapan-grid-item">
                                        <div class="jam-label">Jam {{ $jam }}</div>
                                        <select name="rekapan[{{ $index }}][jam_{{ $jam }}]" class="rekapan-select border rounded p-1 text-sm {{ $class }} focus:ring-1 focus:ring-red-500 focus:border-red-500">
                                            <option value="1" {{ $status == 1 ? 'selected' : '' }}>Hadir</option>
                                            <option value="0" {{ $status == 0 ? 'selected' : '' }}>Alfa</option>
                                            <option value="2" {{ $status == 2 ? 'selected' : '' }}>Sakit</option>
                                            <option value="3" {{ $status == 3 ? 'selected' : '' }}>Izin</option>
                                        </select>
                                    </div>
                                    @endfor

                                    <!-- Kolom Keterangan (Grid Position 8) -->
                                    <div class="rekapan-grid-item">
                                        <div class="keterangan-label">Keterangan</div>
                                        <input type="text" name="rekapan[{{ $index }}][keterangan]" value="{{ $rekapan?->keterangan ?? '' }}" class="keterangan-input" placeholder="Opsional" maxlength="100">
                                    </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </form>
            @endif

            @if($selectedKelas && $santris->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <p class="text-yellow-700">Tidak ada santri di kelas ini.</p>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update select styling on change
            document.querySelectorAll('.rekapan-select').forEach(select => {
                const updateClass = () => {
                    const value = parseInt(select.value);
                    const baseClass = 'rekapan-select border rounded p-1 text-sm ';
                    const colorClass =
                        value === 0 ? 'bg-red-100 border-red-300 text-red-800' :
                        value === 1 ? 'bg-green-100 border-green-300 text-green-800' :
                        value === 2 ? 'bg-yellow-100 border-yellow-300 text-yellow-900' :
                        'bg-blue-100 border-blue-300 text-blue-800';

                    // Update class
                    select.className = baseClass + colorClass;

                    // Update text color for better contrast (yellow)
                    if (value === 2) {
                        select.style.color = '#92400e';
                    } else {
                        select.style.color = '';
                    }
                };

                select.addEventListener('change', updateClass);
                updateClass(); // Initial styling
            });

            // Keterangan input enhancement
            document.querySelectorAll('.keterangan-input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-red-200');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-red-200');
                });

                // Auto-resize height based on content
                input.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 80) + 'px';
                });
            });

            // Form submission handler
            const form = document.getElementById('rekapan-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<span class="flex items-center">Menyimpan...</span>';
                        submitBtn.disabled = true;

                        // Re-enable on completion (you might want to handle this with AJAX)
                        setTimeout(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 3000);
                    }

                    console.log('Form submitted - Saving attendance data...');
                });
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + S to save (if form exists)
                if ((e.ctrlKey || e.metaKey) && e.key === 's' && document.getElementById('rekapan-form')) {
                    e.preventDefault();
                    document.getElementById('rekapan-form').dispatchEvent(new Event('submit'));
                }

                // Tab navigation enhancement for selects
                if (e.key === 'Tab' && e.target.classList.contains('rekapan-select')) {
                    // Focus next select in grid
                    const currentIndex = Array.from(document.querySelectorAll('.rekapan-select')).indexOf(e.target);
                    const nextSelect = document.querySelectorAll('.rekapan-select')[currentIndex + 1];
                    if (nextSelect) {
                        e.preventDefault();
                        nextSelect.focus();
                    }
                }
            });
        });

    </script>
</x-app-layout>
