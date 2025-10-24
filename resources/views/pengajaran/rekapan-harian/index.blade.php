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
            transition: all 0.2s ease-in-out;
        }

        .rekapan-select:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .keterangan-input:hover {
            border-color: #9ca3af;
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
            gap: 0.75rem;
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
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        /* Enhanced status badges */
        .status-badge {
            transition: all 0.2s ease-in-out;
        }

        .status-badge:hover {
            transform: scale(1.05);
        }

        /* Improved mobile-first approach */
        @media (max-width: 1023px) {
            .rekapan-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 1rem;
            }

            .rekapan-select,
            .keterangan-input {
                font-size: 0.8rem;
                padding: 0.75rem 0.5rem;
            }

            .jam-label,
            .keterangan-label {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 767px) {
            .rekapan-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .jam-label,
            .keterangan-label {
                font-size: 0.75rem;
                margin-bottom: 0.4rem;
            }

            .rekapan-select,
            .keterangan-input {
                font-size: 0.85rem;
                padding: 0.85rem 0.5rem;
                min-height: 48px;
            }

            /* Enhanced header actions */
            .header-actions {
                flex-direction: column;
                gap: 0.75rem;
                width: 100%;
            }

            .lihat-laporan-btn {
                width: 100%;
                justify-content: center;
                padding: 0.85rem 1rem;
                font-size: 1rem;
                font-weight: 600;
            }

            /* Improved batch action controls */
            .batch-controls-mobile {
                flex-direction: column;
                gap: 0.75rem;
                width: 100%;
            }

            .batch-controls-mobile select {
                width: 100%;
                padding: 0.75rem;
                font-size: 0.9rem;
                border-radius: 0.5rem;
            }

            .batch-controls-mobile button {
                padding: 0.75rem;
                font-size: 0.9rem;
                font-weight: 600;
                border-radius: 0.5rem;
            }

            .batch-controls-mobile .flex {
                gap: 0.5rem;
                width: 100%;
            }

            .batch-controls-mobile .flex button {
                flex: 1;
            }
        }

        @media (max-width: 480px) {
            .rekapan-grid {
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }

            .rekapan-grid-item {
                min-width: 45%;
            }

            .jam-label,
            .keterangan-label {
                font-size: 0.7rem;
            }

            .rekapan-select,
            .keterangan-input {
                font-size: 0.8rem;
                padding: 0.75rem 0.5rem;
                min-height: 46px;
            }

            /* Enhanced mobile header */
            .mobile-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .mobile-header h1 {
                font-size: 1.75rem;
                line-height: 1.2;
            }

            .mobile-header p {
                font-size: 0.9rem;
                line-height: 1.4;
            }
        }

        /* Enhanced touch targets */
        @media (max-width: 767px) {
            .touch-target {
                min-height: 33px;
                min-width: 33px;
                padding: 0.4rem;
            }

            .form-group-mobile {
                margin-bottom: 1.25rem;
            }

            .form-group-mobile label {
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
                font-weight: 600;
            }

            .form-group-mobile select,
            .form-group-mobile input {
                font-size: 1rem;
                padding: 0.85rem;
                border-radius: 0.5rem;
            }
        }

        /* Enhanced checkbox styling */
        .checkbox-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid #d1d5db;
            border-radius: 0.375rem;
            background-color: #fff;
            transition: all 0.2s;
            cursor: pointer;
            flex-shrink: 0;
        }

        .checkbox-container:hover {
            border-color: #ef4444;
            transform: scale(1.05);
        }

        .checkbox-container.checked {
            background-color: #ef4444;
            border-color: #ef4444;
            transform: scale(1.05);
        }

        .checkbox-container.checked::after {
            content: '';
            display: block;
            width: 0.6rem;
            height: 0.35rem;
            border: 2px solid #fff;
            border-top: 0;
            border-left: 0;
            transform: rotate(45deg);
        }

        /* Enhanced scrollable container */
        .scroll-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
            padding-bottom: 0.5rem;
        }

        .scroll-container::-webkit-scrollbar {
            height: 6px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 3px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 3px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }

        /* Enhanced student cards */
        .student-card {
            transition: all 0.2s ease-in-out;
            border-radius: 0.5rem;
        }

        .student-card:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 767px) {
            .student-card {
                padding: 1.25rem;
                margin-bottom: 0.5rem;
                border: 1px solid #e2e8f0;
            }

            .student-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .student-name-section {
                width: 100%;
            }

            .student-stats {
                align-self: flex-end;
            }
        }

        /* Enhanced button styles */
        .btn-primary {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Loading animation */
        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Enhanced focus states */
        .rekapan-select:focus,
        .keterangan-input:focus,
        .touch-target:focus {
            outline: none;
            ring: 2px;
            ring-color: rgba(239, 68, 68, 0.2);
            ring-offset: 2px;
        }

    </style>

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
            <!-- Enhanced Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex-1 mobile-header">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                            Rekapan Harian Santri
                        </h1>
                        <p class="mt-2 text-slate-600 text-sm sm:text-base">
                            Input rekapan kehadiran santri per hari dengan mudah dan cepat
                        </p>
                    </div>

                    <!-- Enhanced Lihat Laporan Button -->
                    <div class="header-actions flex items-center">
                        <a href="{{ route('pengajaran.rekapan-harian.laporan') }}" class="lihat-laporan-btn btn-success inline-flex items-center px-4 py-2 text-white font-medium rounded-lg transition-all duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 whitespace-nowrap touch-target">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Enhanced Alerts -->
            @if(session('success'))
            <x-alert type="success" :message="session('success')" class="mb-6 transform transition-all duration-300 ease-in-out" />
            @endif

            @if(session('error'))
            <x-alert type="error" :message="session('error')" class="mb-6 transform transition-all duration-300 ease-in-out" />
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg transform transition-all duration-300 ease-in-out">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-semibold">Perhatian:</span>
                </div>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Enhanced Filter Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 mb-6 transform transition-all duration-300 hover:shadow-xl">
                <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-6 items-end">
                    <div class="form-group-mobile">
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 touch-target transition-all duration-200" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $selectedKelas == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-mobile">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 touch-target transition-all duration-200" required>
                    </div>
                    <div>
                        <button type="submit" class="w-full btn-primary text-white font-medium py-2 px-4 rounded-lg transition-all duration-200 touch-target">
                            <span class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Tampilkan Santri
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            @if($santris->isNotEmpty())
            <form action="{{ route('pengajaran.rekapan-harian.store') }}" method="POST" id="rekapan-form">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $selectedKelas }}">
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 transform transition-all duration-300">
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <h3 class="text-lg font-semibold text-gray-900">Input Rekapan Harian</h3>
                            <div class="mt-2 sm:mt-0 flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                                <!-- Enhanced Status Legend -->
                                <div class="flex items-center space-x-4 text-sm bg-white px-3 py-2 rounded-lg border border-slate-200">
                                    @foreach([0 => 'Alfa', 1 => 'Hadir', 2 => 'Sakit', 3 => 'Izin'] as $value => $label)
                                    <div class="flex items-center status-badge">
                                        <span class="inline-block w-3 h-3 rounded-full 
                                                    {{ $value == 0 ? 'bg-red-500' : 
                                                       ($value == 1 ? 'bg-green-500' : 
                                                       ($value == 2 ? 'bg-yellow-500' : 'bg-blue-500')) }} mr-2 shadow-sm"></span>
                                        <span class="font-medium text-slate-700">{{ $label }}</span>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Enhanced Batch Action Controls -->
                                <div x-data="{ 
                                    selectedStatus: '',
                                    applyToAll: function() {
                                        if (!this.selectedStatus) {
                                            this.showToast('Pilih status terlebih dahulu', 'warning');
                                            return;
                                        }
                                        
                                        document.querySelectorAll('.rekapan-select').forEach(select => {
                                            select.value = this.selectedStatus;
                                            select.dispatchEvent(new Event('change'));
                                        });
                                        this.showToast('Status diterapkan ke semua santri', 'success');
                                    },
                                    applyToSelected: function() {
                                        if (!this.selectedStatus) {
                                            this.showToast('Pilih status terlebih dahulu', 'warning');
                                            return;
                                        }
                                        
                                        let appliedCount = 0;
                                        document.querySelectorAll('input[type=checkbox]').forEach(checkbox => {
                                            if (checkbox.checked) {
                                                const container = checkbox.closest('.student-card');
                                                if (container) {
                                                    container.querySelectorAll('.rekapan-select').forEach(select => {
                                                        select.value = this.selectedStatus;
                                                        select.dispatchEvent(new Event('change'));
                                                    });
                                                    appliedCount++;
                                                }
                                            }
                                        });
                                        
                                        if (appliedCount > 0) {
                                            this.showToast(`Status diterapkan ke ${appliedCount} santri`, 'success');
                                        } else {
                                            this.showToast('Pilih santri terlebih dahulu', 'warning');
                                        }
                                    },
                                    showToast: function(message, type) {
                                        // Simple toast implementation
                                        const toast = document.createElement('div');
                                        toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white font-medium transform transition-all duration-300 ${
                                            type === 'success' ? 'bg-green-500' : 'bg-yellow-500'
                                        }`;
                                        toast.textContent = message;
                                        document.body.appendChild(toast);
                                        
                                        setTimeout(() => {
                                            toast.remove();
                                        }, 3000);
                                    }
                                }" class="flex flex-col sm:flex-row items-center gap-2 batch-controls-mobile w-full sm:w-auto">
                                    <select x-model="selectedStatus" class="rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm touch-target transition-all duration-200">
                                        <option value="">Pilih Status</option>
                                        <option value="1">Hadir</option>
                                        <option value="0">Alfa</option>
                                        <option value="2">Sakit</option>
                                        <option value="3">Izin</option>
                                    </select>
                                    <div class="flex gap-2 w-full sm:w-auto">
                                        <button type="button" @click="applyToAll" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-all duration-200 touch-target font-medium shadow-sm">
                                            Terapkan Semua
                                        </button>
                                        <button type="button" @click="applyToSelected" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm transition-all duration-200 touch-target font-medium shadow-sm">
                                            Terapkan Terpilih
                                        </button>
                                    </div>
                                </div>

                                @if (auth()->user()->role === 'pengajaran' || auth()->user()->role === 'admin')
                                <button type="submit" class="btn-primary text-white font-medium py-2 px-6 rounded-lg transition-all duration-200 whitespace-nowrap touch-target shadow-sm">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Simpan Rekapan
                                    </span>
                                </button>
                                @else
                                <p class="text-red-500 font-medium text-sm bg-red-50 px-3 py-2 rounded-lg border border-red-200">
                                    Anda tidak memiliki akses untuk menyimpan.
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Student List -->
                    <div class="divide-y divide-slate-200">
                        @foreach($santris as $index => $santri)
                        @php
                        $rekapan = $rekapanData[$santri->id] ?? null;
                        @endphp
                        <div class="p-6 student-card" x-data>
                            <input type="hidden" name="rekapan[{{ $index }}][santri_id]" value="{{ $santri->id }}">

                            <!-- Enhanced Student Header -->
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200 student-header">
                                <div class="flex items-center gap-4 student-name-section">
                                    <label class="checkbox-container flex items-center justify-center cursor-pointer touch-target transform transition-all duration-200">
                                        <input type="checkbox" class="absolute opacity-0 w-0 h-0">
                                    </label>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 text-lg">{{ $santri->nama }}</div>
                                        <div class="text-sm text-gray-500 mt-1">NIS: {{ $santri->nis }}</div>
                                    </div>
                                </div>
                                <!-- Enhanced Stats -->
                                <div class="student-stats">
                                    @php
                                    $hadirCount = 0;
                                    for($j=1; $j<=7; $j++) { $status=$rekapan ? ($rekapan->{"jam_$j"} ?? 1) : 1;
                                        if($status == 1) $hadirCount++;
                                        }
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gradient-to-r from-green-100 to-green-50 text-green-800 border border-green-200 status-badge">
                                            {{ $hadirCount }}/7 Hadir
                                        </span>
                                </div>
                            </div>

                            <!-- Enhanced Rekapan Grid -->
                            <div class="scroll-container sm:block">
                                <div class="rekapan-grid sm:min-w-0">
                                    @for($jam = 1; $jam <= 7; $jam++) @php $status=$rekapan ? ($rekapan->{"jam_$jam"} ?? 1) : 1;
                                        $class = match($status) {
                                        0 => 'bg-red-100 border-red-300 text-red-800 hover:bg-red-200',
                                        1 => 'bg-green-100 border-green-300 text-green-800 hover:bg-green-200',
                                        2 => 'bg-yellow-100 border-yellow-300 text-yellow-900 hover:bg-yellow-200',
                                        3 => 'bg-blue-100 border-blue-300 text-blue-800 hover:bg-blue-200',
                                        default => 'bg-green-100 border-green-300 text-green-800 hover:bg-green-200'
                                        };
                                        @endphp
                                        <div class="rekapan-grid-item">
                                            <div class="jam-label">Jam {{ $jam }}</div>
                                            <select name="rekapan[{{ $index }}][jam_{{ $jam }}]" class="rekapan-select border-2 rounded-lg p-2 text-sm font-medium {{ $class }} focus:ring-2 focus:ring-red-500 focus:border-red-500 touch-target transition-all duration-200">
                                                <option value="1" {{ $status == 1 ? 'selected' : '' }}>Hadir</option>
                                                <option value="0" {{ $status == 0 ? 'selected' : '' }}>Alfa</option>
                                                <option value="2" {{ $status == 2 ? 'selected' : '' }}>Sakit</option>
                                                <option value="3" {{ $status == 3 ? 'selected' : '' }}>Izin</option>
                                            </select>
                                        </div>
                                        @endfor

                                        <!-- Enhanced Keterangan Field -->
                                        <div class="rekapan-grid-item">
                                            <div class="keterangan-label">Keterangan</div>
                                            <input type="text" name="rekapan[{{ $index }}][keterangan]" value="{{ $rekapan?->keterangan ?? '' }}" class="keterangan-input rounded-lg" placeholder="Catatan opsional..." maxlength="100">
                                        </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </form>
            @endif

            @if($selectedKelas && $santris->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-8 text-center transform transition-all duration-300">
                <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 text-yellow-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">Tidak Ada Santri</h3>
                    <p class="text-yellow-700">Tidak ada santri yang terdaftar di kelas ini.</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced select styling with better visual feedback
            document.querySelectorAll('.rekapan-select').forEach(select => {
                const updateClass = () => {
                    const value = parseInt(select.value);
                    const baseClass = 'rekapan-select border-2 rounded-lg p-2 text-sm font-medium focus:ring-2 focus:ring-red-500 focus:border-red-500 touch-target transition-all duration-200 ';
                    const colorClass =
                        value === 0 ? 'bg-red-100 border-red-300 text-red-800 hover:bg-red-200' :
                        value === 1 ? 'bg-green-100 border-green-300 text-green-800 hover:bg-green-200' :
                        value === 2 ? 'bg-yellow-100 border-yellow-300 text-yellow-900 hover:bg-yellow-200' :
                        'bg-blue-100 border-blue-300 text-blue-800 hover:bg-blue-200';

                    select.className = baseClass + colorClass;

                    // Enhanced text color for better contrast
                    if (value === 2) {
                        select.style.color = '#92400e';
                        select.style.fontWeight = '600';
                    } else {
                        select.style.color = '';
                        select.style.fontWeight = '500';
                    }
                };

                select.addEventListener('change', updateClass);
                select.addEventListener('focus', () => select.classList.add('ring-2', 'ring-red-200'));
                select.addEventListener('blur', () => select.classList.remove('ring-2', 'ring-red-200'));
                updateClass();
            });

            // Enhanced keterangan input
            document.querySelectorAll('.keterangan-input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-red-200', 'transform', 'scale-105');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-red-200', 'transform', 'scale-105');
                });

                // Auto-resize with limits
                input.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                });
            });

            // Enhanced form submission
            const form = document.getElementById('rekapan-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<span class="flex items-center"><span class="loading-spinner mr-2"></span>Menyimpan...</span>';
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                        // Re-enable after 3 seconds (for demo)
                        setTimeout(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                        }, 3000);
                    }
                });
            }

            // Enhanced keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + S to save
                if ((e.ctrlKey || e.metaKey) && e.key === 's' && document.getElementById('rekapan-form')) {
                    e.preventDefault();
                    document.getElementById('rekapan-form').dispatchEvent(new Event('submit'));
                }

                // Enhanced tab navigation
                if (e.key === 'Tab' && e.target.classList.contains('rekapan-select')) {
                    const currentIndex = Array.from(document.querySelectorAll('.rekapan-select')).indexOf(e.target);
                    const nextSelect = document.querySelectorAll('.rekapan-select')[currentIndex + 1];
                    if (nextSelect) {
                        e.preventDefault();
                        nextSelect.focus();
                        nextSelect.classList.add('ring-2', 'ring-red-200');
                    }
                }
            });

            // Enhanced checkbox functionality
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const container = this.closest('.checkbox-container');
                    const studentCard = this.closest('.student-card');

                    if (container) {
                        container.classList.toggle('checked', this.checked);
                    }

                    if (studentCard) {
                        studentCard.classList.toggle('bg-blue-50', this.checked);
                        studentCard.classList.toggle('border-blue-300', this.checked);
                    }
                });

                // Initialize
                if (checkbox.checked) {
                    const container = checkbox.closest('.checkbox-container');
                    const studentCard = checkbox.closest('.student-card');
                    if (container) container.classList.add('checked');
                    if (studentCard) {
                        studentCard.classList.add('bg-blue-50', 'border-blue-300');
                    }
                }
            });

            // Enhanced mobile optimizations
            const handleMobileOptimizations = () => {
                const screenWidth = window.innerWidth;

                // Adjust grid layout
                const grids = document.querySelectorAll('.rekapan-grid');
                grids.forEach(grid => {
                    if (screenWidth <= 480) {
                        grid.style.gridTemplateColumns = '1fr 1fr';
                        grid.style.gap = '1rem';
                    } else if (screenWidth <= 768) {
                        grid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                        grid.style.gap = '1rem';
                    } else {
                        grid.style.gridTemplateColumns = 'repeat(8, 1fr)';
                        grid.style.gap = '0.75rem';
                    }
                });

                // Add visual feedback for scrollable containers
                const scrollContainers = document.querySelectorAll('.scroll-container');
                scrollContainers.forEach(container => {
                    if (container.scrollWidth > container.clientWidth) {
                        container.style.background = 'linear-gradient(90deg, transparent 95%, #f1f5f9 100%)';
                    }
                });
            };

            // Initialize with debounce
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(handleMobileOptimizations, 250);
            });
            handleMobileOptimizations();

            // Add smooth scrolling for better UX
            const smoothScrollTo = (element) => {
                element.scrollIntoView({
                    behavior: 'smooth'
                    , block: 'center'
                });
            };

            // Enhanced focus management
            document.querySelectorAll('.rekapan-select, .keterangan-input').forEach(element => {
                element.addEventListener('focus', function() {
                    smoothScrollTo(this);
                });
            });
        });

    </script>
</x-app-layout>
