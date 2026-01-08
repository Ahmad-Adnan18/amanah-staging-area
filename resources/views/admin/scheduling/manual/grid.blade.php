{{-- File: resources/views/admin/scheduling/manual/grid.blade.php --}}
<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-4 md:py-8 px-3 sm:px-4 lg:px-8">
            <!-- Header dengan Bulk Actions -->
            <div class="bg-white rounded-xl md:rounded-2xl shadow-lg border border-slate-200 p-4 md:p-6 mb-6 md:mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-0">
                    <div class="text-center md:text-left">
                        <h1 class="text-xl md:text-3xl font-bold tracking-tight text-gray-900">Input Jadwal Manual</h1>
                        <p class="mt-1 text-sm md:text-base text-slate-600">Input jadwal secara cepat dengan grid yang dapat diedit</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 md:gap-3">
                        <!-- Sync Ruangan Button - PERBAIKI STYLING -->
                        <button type="button" onclick="fixRoomSync()" class="inline-flex items-center justify-center px-3 py-2 md:px-4 md:py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-700 active:bg-blue-800 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm">
                            <svg class="w-3 h-3 md:w-4 md:h-4 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span class="text-xs">Sync Ruangan</span>
                        </button>
                        <!-- Bulk Actions -->
                        <button type="button" id="bulkSaveBtn" class="inline-flex items-center justify-center px-3 py-2 md:px-4 md:py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-3 h-3 md:w-4 md:h-4 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-xs">Simpan Semua</span>
                        </button>
                    </div>
                </div>

                <!-- Filter Controls -->
                <div class="mt-4 md:mt-6 grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">Filter Kelas</label>
                        <select id="classFilter" class="w-full text-sm md:text-base border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">Semua Kelas</option>
                            @foreach($classes ?? [] as $class)
                            <option value="class-{{ $class->id }}">{{ $class->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">Mode Input</label>
                        <select id="inputMode" class="w-full text-sm md:text-base border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="single">Input Single</option>
                            <option value="bulk">Bulk Input</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Grid Container -->
            <div class="bg-white rounded-xl md:rounded-2xl shadow-lg border border-slate-200">
                <!-- Loading State -->
                <div id="loadingIndicator" class="hidden p-6 md:p-8 text-center">
                    <div class="animate-spin rounded-full h-8 w-8 md:h-12 md:w-12 border-b-2 border-red-600 mx-auto"></div>
                    <p class="mt-2 text-sm md:text-base text-gray-600">Memuat jadwal...</p>
                </div>

                <!-- Grid Content -->
                <div id="scheduleGrid" class="p-3 md:p-6 overflow-x-auto">
                    <!-- Grid akan di-load via AJAX -->
                </div>

                <!-- Bulk Edit Panel -->
                <div id="bulkEditPanel" class="hidden border-t border-slate-200 bg-slate-50 p-3 md:p-4 rounded-b-xl md:rounded-b-2xl relative z-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6">
                        <div class="flex-shrink-0 text-center md:text-left">
                            <h4 class="font-semibold text-gray-900">Bulk Edit</h4>
                            <p class="text-xs md:text-sm text-gray-600" id="selectedCellsCount">0 sel terpilih</p>
                        </div>
                        <div class="bulk-edit-controls-container w-full md:w-auto">
                            <div class="flex flex-col sm:flex-row gap-3 md:gap-3 flex-wrap md:flex-nowrap">
                                <div class="bulk-select-container w-full sm:w-48 md:w-52">
                                    <select id="bulkSubject" class="w-full text-xs md:text-sm border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 tom-select-subject">
                                        <option value="">Pilih Mapel</option>
                                        @php
                                        $uniqueSubjects = [];
                                        @endphp
                                        @foreach($subjects ?? [] as $subject)
                                        @php
                                        $key = $subject->nama_pelajaran . '|' . $subject->tingkatan;
                                        @endphp
                                        @if(!in_array($key, $uniqueSubjects))
                                        @php
                                        $uniqueSubjects[] = $key;
                                        @endphp
                                        <option value="{{ $subject->id }}">{{ $subject->nama_pelajaran }} ({{ $subject->tingkatan }})</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="bulk-select-container w-full sm:w-48 md:w-52">
                                    <select id="bulkTeacher" class="w-full text-xs md:text-sm border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 tom-select-teacher">
                                        <option value="">Pilih Guru</option>
                                        @php
                                        $uniqueTeachers = [];
                                        @endphp
                                        @foreach($teachers ?? [] as $teacher)
                                        @php
                                        $key = $teacher->name . '|' . $teacher->teacher_code;
                                        @endphp
                                        @if(!in_array($key, $uniqueTeachers))
                                        @php
                                        $uniqueTeachers[] = $key;
                                        @endphp
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->teacher_code }})</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="bulk-info-container w-full sm:w-auto flex-grow">
                                    <div class="w-full text-xs md:text-sm border-gray-300 rounded-md bg-gray-100 p-2 md:p-2.5 text-center text-gray-700 h-full flex items-center justify-center min-h-[42px] md:min-h-[46px]" id="bulkRoomInfo">
                                        Ruangan ditentukan otomatis dari kelas
                                    </div>
                                </div>
                                <div class="bulk-button-container w-full sm:w-auto">
                                    <button type="button" id="applyBulkBtn" class="w-full px-3 py-2 bg-blue-600 text-white rounded-md text-xs md:text-sm hover:bg-blue-500 transition-all duration-200 transform hover:scale-105">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-4 md:mt-6 grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4">
                <div class="bg-white rounded-lg shadow p-3 md:p-4 text-center transition-all duration-200 hover:shadow-lg">
                    <div class="text-lg md:text-2xl font-bold text-gray-900" id="totalSlots">0</div>
                    <div class="text-xs md:text-sm text-gray-600">Total Slot</div>
                </div>
                <div class="bg-white rounded-lg shadow p-3 md:p-4 text-center transition-all duration-200 hover:shadow-lg">
                    <div class="text-lg md:text-2xl font-bold text-green-600" id="filledSlots">0</div>
                    <div class="text-xs md:text-sm text-gray-600">Terisi</div>
                </div>
                <div class="bg-white rounded-lg shadow p-3 md:p-4 text-center transition-all duration-200 hover:shadow-lg">
                    <div class="text-lg md:text-2xl font-bold text-red-600" id="emptySlots">0</div>
                    <div class="text-xs md:text-sm text-gray-600">Kosong</div>
                </div>
                <div class="bg-white rounded-lg shadow p-3 md:p-4 text-center transition-all duration-200 hover:shadow-lg">
                    <div class="text-lg md:text-2xl font-bold text-yellow-600" id="conflictSlots">0</div>
                    <div class="text-xs md:text-sm text-gray-600">Konflik</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Modal untuk Quick Edit -->
    <div id="quickEditModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-3 sm:px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 backdrop-blur-sm" id="quickEditModalBackdrop"></div>

            <!-- Modal panel -->
            <div id="quickEditModalPanel" class="relative inline-block w-full max-w-md px-3 sm:px-4 pt-4 pb-3 sm:pt-5 sm:pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl sm:rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:p-6 opacity-0 scale-95">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-red-100 rounded-lg">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900" id="modalTitle">Edit Jadwal</h3>
                            <p class="text-xs sm:text-sm text-gray-500" id="modalSubtitle">
                                <span class="block sm:inline">Kelas: <span id="modalKelasInfo">-</span></span>
                                <span class="block sm:inline">Hari: <span id="modalHariInfo">-</span></span>
                                <span class="block sm:inline">Jam: <span id="modalJamInfo">-</span></span>
                            </p>
                        </div>
                    </div>
                    <button type="button" id="closeQuickEditModal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form id="quickEditForm" class="space-y-3 sm:space-y-4">
                    <input type="hidden" name="schedule_id" id="edit_schedule_id">
                    <input type="hidden" name="kelas_id" id="edit_kelas_id">
                    <input type="hidden" name="day_of_week" id="edit_day_of_week">
                    <input type="hidden" name="time_slot" id="edit_time_slot">

                    <div class="space-y-3 sm:space-y-4">
                        <!-- Mata Pelajaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Mata Pelajaran</label>
                            <div class="relative">
                                <select name="mata_pelajaran_id" id="edit_mata_pelajaran_id" required class="w-full pl-3 pr-8 sm:pr-10 py-2 text-sm sm:text-base border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 tom-select-subject">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @php
                                    $uniqueSubjects = [];
                                    @endphp
                                    @foreach($subjects as $subject)
                                    @php
                                    $key = $subject->nama_pelajaran . '|' . $subject->tingkatan;
                                    @endphp
                                    @if(!in_array($key, $uniqueSubjects))
                                    @php
                                    $uniqueSubjects[] = $key;
                                    @endphp
                                    <option value="{{ $subject->id }}">{{ $subject->nama_pelajaran }} ({{ $subject->tingkatan }})</option>
                                    @endif
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Guru -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Guru</label>
                            <div class="relative">
                                <select name="teacher_id" id="edit_teacher_id" required class="w-full pl-3 pr-8 sm:pr-10 py-2 text-sm sm:text-base border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 tom-select-teacher">
                                    <option value="">Pilih Guru</option>
                                    @php
                                    $uniqueTeachers = [];
                                    @endphp
                                    @foreach($teachers ?? [] as $teacher)
                                    @php
                                    $key = $teacher->name . '|' . $teacher->teacher_code;
                                    @endphp
                                    @if(!in_array($key, $uniqueTeachers))
                                    @php
                                    $uniqueTeachers[] = $key;
                                    @endphp
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->teacher_code }})</option>
                                    @endif
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Ruangan (diambil otomatis dari kelas) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">Ruangan</label>
                            <div class="w-full pl-3 pr-8 sm:pr-10 py-2 text-sm sm:text-base border-gray-300 rounded-lg bg-gray-100 text-gray-700" id="edit_room_info">
                                Akan otomatis ditentukan dari kelas
                            </div>
                            <input type="hidden" name="room_id" id="edit_room_id" value="">
                        </div>
                    </div>
                </form>

                <!-- Actions -->
                <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row justify-between gap-2 sm:gap-3">
                    <button type="button" id="deleteQuickEditBtn" class="hidden w-full sm:w-auto px-3 sm:px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                        Hapus
                    </button>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 ml-auto">
                        <button type="button" id="cancelEditBtn" class="w-full sm:w-auto px-3 sm:px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            Batal
                        </button>
                        <button type="button" id="saveQuickEditBtn" class="w-full sm:w-auto px-3 sm:px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <span id="saveButtonText">Simpan Jadwal</span>
                            <div id="saveButtonLoading" class="hidden animate-spin rounded-full h-3 w-3 sm:h-4 sm:w-4 border-b-2 border-white"></div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Toast Notifications -->
    <div id="toastContainer" class="fixed top-2 right-2 sm:top-4 sm:right-4 z-50 space-y-2 max-w-xs sm:max-w-sm"></div>

    @push('scripts')
    <script>
        // Function untuk sync ruangan
        // Function untuk sync ruangan - PAKAI TOAST NOTIFICATION
        function fixRoomSync() {
            // Gunakan confirm dialog yang sama seperti lainnya
            const dialogId = 'confirm-dialog-' + Date.now();
            const dialog = document.createElement('div');
            dialog.id = dialogId;
            dialog.className = 'fixed inset-0 z-50 overflow-y-auto';
            dialog.innerHTML = `
        <div class="flex items-center justify-center min-h-screen px-3 sm:px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 backdrop-blur-sm"></div>
            <div class="relative inline-block w-full max-w-md px-3 sm:px-4 pt-4 pb-3 sm:pt-5 sm:pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl sm:rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:p-6">
                <div class="flex items-center mb-3 sm:mb-4">
                    <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-yellow-100 rounded-lg">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900">Sync Ruangan</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Yakin ingin sync semua ruangan jadwal dengan ruangan kelas? Ini akan memperbaiki ruangan yang tidak sesuai.</p>
                    </div>
                </div>
                <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                    <button type="button" class="w-full sm:w-auto px-3 sm:px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200" id="cancelBtn">
                        Batal
                    </button>
                    <button type="button" class="w-full sm:w-auto px-3 sm:px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-all duration-200" id="confirmBtn">
                        Ya, Sync Ruangan
                    </button>
                </div>
            </div>
        </div>
    `;

            document.body.appendChild(dialog);

            // Event listener dengan closure yang benar
            const cancelBtn = dialog.querySelector('#cancelBtn');
            const confirmBtn = dialog.querySelector('#confirmBtn');

            const closeDialog = () => {
                dialog.classList.add('opacity-0');
                setTimeout(() => {
                    if (dialog.parentNode) dialog.remove();
                }, 300);
            };

            cancelBtn.addEventListener('click', closeDialog);

            confirmBtn.addEventListener('click', () => {
                closeDialog();

                // Show loading state pada tombol
                const button = event.target.closest('button') || document.querySelector('button[onclick="fixRoomSync()"]');
                const originalText = button.innerHTML;
                button.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Syncing...';
                button.disabled = true;

                // Proses sync ruangan
                fetch('/admin/scheduling/manual/fix-rooms', {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            , 'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        // Cek jika response adalah HTML (error)
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            return response.text().then(html => {
                                throw new Error('Server returned HTML instead of JSON. Might be authentication issue.');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Gunakan toast success seperti di class ScheduleGrid
                            if (window.scheduleGrid) {
                                window.scheduleGrid.showSuccess('✅ ' + data.message);
                            } else {
                                // Fallback jika scheduleGrid tidak tersedia
                                showCustomToast('✅ ' + data.message, 'success');
                            }

                            // Reload grid untuk menampilkan perubahan
                            if (window.scheduleGrid) {
                                window.scheduleGrid.loadGrid();
                            }
                        } else {
                            if (window.scheduleGrid) {
                                window.scheduleGrid.showError('❌ ' + data.message);
                            } else {
                                showCustomToast('❌ ' + data.message, 'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Sync error:', error);
                        if (window.scheduleGrid) {
                            window.scheduleGrid.showError('❌ Terjadi error: ' + error.message);
                        } else {
                            showCustomToast('❌ Terjadi error: ' + error.message, 'error');
                        }
                    })
                    .finally(() => {
                        // Restore button
                        button.innerHTML = originalText;
                        button.disabled = false;
                    });
            });

            // Close on backdrop click
            dialog.addEventListener('click', (e) => {
                if (e.target === dialog) closeDialog();
            });

            // Escape key
            const escapeHandler = (e) => {
                if (e.key === 'Escape') {
                    closeDialog();
                    document.removeEventListener('keydown', escapeHandler);
                }
            };
            document.addEventListener('keydown', escapeHandler);
        }

        // Fallback function untuk toast jika scheduleGrid tidak tersedia
        function showCustomToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();

            const icons = {
                success: `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`
                , error: `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`
                , info: `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
            };

            const colors = {
                success: 'bg-green-50 border-green-200 text-green-800'
                , error: 'bg-red-50 border-red-200 text-red-800'
                , info: 'bg-blue-50 border-blue-200 text-blue-800'
            };

            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `p-3 rounded-lg border shadow-lg transform transition-all duration-300 ${colors[type]} opacity-0 translate-x-full text-sm`;
            toast.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                ${icons[type]}
            </div>
            <div class="ml-2 flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button type="button" class="ml-2 -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex h-6 w-6 hover:bg-gray-100 transition-colors duration-200" onclick="document.getElementById('${toastId}').remove()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

            toastContainer.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('opacity-0', 'translate-x-full');
                toast.classList.add('opacity-100', 'translate-x-0');
            }, 10);

            // Auto remove after 4 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.classList.remove('opacity-100', 'translate-x-0');
                    toast.classList.add('opacity-0', 'translate-x-full');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, 4000);
        }
            class ScheduleGrid {
                constructor() {
                    this.selectedCells = new Set();
                    this.currentData = {};
                    this.isSaving = false;
                    this.activeDay = 1; // Default Senin
                    this.viewMode = 'desktop'; // 'desktop' or 'mobile'

                    // Bind methods
                    this.handleSlotClick = this.handleSlotClick.bind(this);
                    this.toggleBulkSelection = this.toggleBulkSelection.bind(this);
                    this.openQuickEdit = this.openQuickEdit.bind(this);
                    this.saveQuickEdit = this.saveQuickEdit.bind(this);
                    this.deleteSchedule = this.deleteSchedule.bind(this);
                    this.applyBulkEdit = this.applyBulkEdit.bind(this);
                    this.applyFilters = this.applyFilters.bind(this);
                    this.clearClass = this.clearClass.bind(this);
                    this.closeQuickEdit = this.closeQuickEdit.bind(this);
                    this.showBulkSelectionGuide = this.showBulkSelectionGuide.bind(this);
                    this.showSuccess = this.showSuccess.bind(this);
                    this.showError = this.showError.bind(this);
                    this.clearBulkSelection = this.clearBulkSelection.bind(this);
                    this.setSaveButtonLoading = this.setSaveButtonLoading.bind(this);
                    this.showToast = this.showToast.bind(this);
                    this.showConfirmDialog = this.showConfirmDialog.bind(this);
                    this.truncateText = this.truncateText.bind(this);
                    this.findSchedule = this.findSchedule.bind(this);
                    this.findScheduleById = this.findScheduleById.bind(this);
                    this.updateStats = this.updateStats.bind(this);
                    this.showLoading = this.showLoading.bind(this);
                    this.hideLoading = this.hideLoading.bind(this);
                    this.updateBulkPanel = this.updateBulkPanel.bind(this);
                    this.setActiveDay = this.setActiveDay.bind(this);
                    this.checkViewMode = this.checkViewMode.bind(this);

                    this.init();
                }

                init() {
                    this.checkViewMode();
                    window.addEventListener('resize', () => {
                        const oldMode = this.viewMode;
                        this.checkViewMode();
                        if (oldMode !== this.viewMode && this.currentData.days) {
                            this.renderGrid(this.currentData);
                        }
                    });
                    this.loadGrid();
                    this.setupEventListeners();
                }

                checkViewMode() {
                    this.viewMode = window.innerWidth < 1024 ? 'mobile' : 'desktop';
                }

                async loadGrid() {
                    this.showLoading();
                    try {
                        console.log('Fetching grid data...'); // Debug
                        const response = await fetch('{{ route("admin.scheduling.manual.grid.data") }}');
                        console.log('Response status:', response.status); // Debug
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        console.log('Grid Data Received:', data); // Debug
                        
                        this.currentData = data;
                        this.renderTabs(data.days);
                        this.renderGrid(data);
                        this.updateStats(data);
                    } catch (error) {
                        console.error('Error loading grid:', error);
                        this.showError('Gagal memuat data jadwal: ' + error.message);
                    } finally {
                        this.hideLoading();
                    }
                }

                renderTabs(days) {
                    const container = document.getElementById('dayTabsContainer');
                    if (!container) {
                        // Insert tabs container before grid if not exists
                        const gridContainer = document.getElementById('scheduleGrid');
                        const tabsDiv = document.createElement('div');
                        tabsDiv.id = 'dayTabsContainer';
                        tabsDiv.className = 'flex flex-wrap gap-2 mb-4 px-3 md:px-0';
                        gridContainer.parentNode.insertBefore(tabsDiv, gridContainer);
                    }

                    const tabsContainer = document.getElementById('dayTabsContainer');
                    let html = '';
                    
                    // Render tabs 1-6
                    for (let i = 1; i <= 6; i++) {
                        const dayName = days[i];
                        const isActive = this.activeDay === i;
                        const activeClass = isActive ? 'bg-red-600 text-white shadow-md ring-2 ring-red-200' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200';
                        
                        html += `
                            <button onclick="window.scheduleGrid.setActiveDay(${i})" 
                                class="flex-1 min-w-[100px] md:min-w-0 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 transform hover:-translate-y-0.5 ${activeClass}">
                                ${dayName}
                            </button>
                        `;
                    }
                    
                    tabsContainer.innerHTML = html;
                }

                setActiveDay(day) {
                    this.activeDay = day;
                    this.renderTabs(this.currentData.days);
                    this.renderGrid(this.currentData);
                }

                renderGrid(data) {
                    const gridContainer = document.getElementById('scheduleGrid');
                    
                    if (this.viewMode === 'mobile') {
                        this.renderMobileGrid(data, gridContainer);
                    } else {
                        this.renderDesktopGrid(data, gridContainer);
                    }
                }

                renderDesktopGrid(data, container) {
                    console.log('Rendering Desktop Grid...', data); // Debug
                    if (!data.classes || data.classes.length === 0) {
                        console.warn('No classes found in data');
                        container.innerHTML = '<div class="p-8 text-center text-gray-500">Tidak ada data kelas ditemukan</div>';
                        return;
                    }

                    let html = '';
                    // 9 columns: 1 Class name + 7 Time Slots + 1 Action
                    const totalCols = 9; 
                    
                    // Header
                    html += `<div class="min-w-max grid grid-cols-9 gap-2 mb-4">`;
                    html += `<div class="p-3 min-w-[180px] text-sm font-bold text-gray-700 bg-slate-100 rounded-xl flex items-center justify-center border border-slate-200 shadow-sm sticky left-0 z-20">Kelas</div>`;
                    
                    for (let slot = 1; slot <= 7; slot++) {
                        html += `<div class="p-3 min-w-[160px] text-center text-sm font-bold text-gray-700 bg-slate-50 rounded-xl border border-slate-200">Jam ${slot}</div>`;
                    }
                    html += `<div class="p-3 min-w-[80px] text-center text-sm font-bold text-gray-700 bg-slate-50 rounded-xl border border-slate-200">Aksi</div>`;
                    html += '</div>';

                    // Rows
                    data.classes.forEach(kelas => {
                         // Filter if searching
                         const classFilter = document.getElementById('classFilter').value;
                         if (classFilter && !(`class-${kelas.id}` === classFilter)) return;

                        html += `<div class="min-w-max grid grid-cols-9 gap-2 mb-2 group class-row class-${kelas.id}">`;
                        
                        // Class Name
                        html += `<div class="p-3 min-w-[180px] text-sm font-bold text-gray-800 bg-white rounded-xl border border-slate-200 flex items-center justify-center shadow-sm group-hover:shadow-md transition-all sticky left-0 z-20">
                            ${kelas.nama_kelas}
                        </div>`;

                        for (let timeSlot = 1; timeSlot <= 7; timeSlot++) {
                            const schedule = this.findSchedule(data.schedules, kelas.id, this.activeDay, timeSlot);
                            html += this.renderTimeSlot(schedule, kelas.id, this.activeDay, timeSlot);
                        }

                        // Action
                         html += `<div class="p-2 flex items-center justify-center">
                            <button type="button" class="clear-class-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Semua Jadwal Kelas Ini" data-class-id="${kelas.id}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>`;
                        
                        html += '</div>';
                    });
                    
                    container.innerHTML = html;
                    
                    // Re-apply styles if needed
                    container.className = 'p-4 overflow-x-auto';
                }

                renderMobileGrid(data, container) {
                    let html = '<div class="space-y-4">';
                    
                    data.classes.forEach(kelas => {
                         // Filter
                         const classFilter = document.getElementById('classFilter').value;
                         if (classFilter && !(`class-${kelas.id}` === classFilter)) return;

                        html += `<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden class-row class-${kelas.id}">
                            <div class="bg-gradient-to-r from-slate-50 to-white px-4 py-3 border-b border-slate-100 flex justify-between items-center">
                                <h3 class="font-bold text-slate-800">${kelas.nama_kelas}</h3>
                                <button type="button" class="clear-class-btn text-xs text-red-600 hover:text-red-800 font-medium" data-class-id="${kelas.id}">
                                    Reset
                                </button>
                            </div>
                            <div class="p-3 grid grid-cols-2 sm:grid-cols-3 gap-3">`;
                                
                        for (let timeSlot = 1; timeSlot <= 7; timeSlot++) {
                            const schedule = this.findSchedule(data.schedules, kelas.id, this.activeDay, timeSlot);
                            html += `<div class="flex flex-col gap-1">
                                <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold ml-1">Jam ${timeSlot}</div>
                                ${this.renderTimeSlot(schedule, kelas.id, this.activeDay, timeSlot)}
                            </div>`;
                        }
                        
                        html += `</div></div>`;
                    });

                    html += '</div>';
                    container.innerHTML = html;
                    container.className = 'p-3';
                }

                renderTimeSlot(schedule, kelasId, day, timeSlot) {
                    const hasSchedule = schedule !== null && schedule !== undefined;
                    const hasConflict = hasSchedule && schedule.has_conflict;

                    let className = 'relative w-full min-w-[160px] p-2 h-[90px] rounded-xl border transition-all duration-200 cursor-pointer time-slot-cell group flex flex-col justify-center items-center text-center shadow-sm hover:shadow-md ';
                    let content = '';

                    if (hasSchedule) {
                        className += hasConflict ?
                            'bg-red-50 border-red-200 hover:border-red-300' :
                            'bg-green-50 border-green-200 hover:border-green-300';
                        
                        const conflictIndicator = hasConflict ? 
                            `<div class="absolute top-1 right-1 h-2 w-2 rounded-full bg-red-500 animate-pulse"></div>` : '';

                        content = `
                            ${conflictIndicator}
                            <div class="text-xs font-bold text-slate-800 line-clamp-2 leading-tight w-full">${schedule.subject_name || 'Mapel'}</div>
                            <div class="text-[10px] font-medium text-slate-500 mt-1 w-full truncate">${schedule.teacher_name || 'Noteacher'}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">${schedule.room_name || '-'}</div>
                        `;
                    } else {
                        className += 'bg-slate-50 border-slate-100 hover:border-slate-300 border-dashed';
                        content = `
                            <svg class="w-5 h-5 text-slate-300 group-hover:text-slate-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        `;
                    }

                    return `
                        <div class="${className}" 
                             data-kelas="${kelasId}" 
                             data-day="${day}" 
                             data-slot="${timeSlot}"
                             data-schedule-id="${hasSchedule ? schedule.id : ''}">
                            ${content}
                        </div>
                    `;
                }

                handleSlotClick(element, event) {
                    const inputMode = document.getElementById('inputMode').value;
                    
                    if (inputMode === 'bulk') {
                        this.toggleBulkSelection(element);
                    } else {
                        this.openQuickEdit(element);
                    }
                }

                setupEventListeners() {
                    const gridContainer = document.getElementById('scheduleGrid');

                    gridContainer.addEventListener('click', (e) => {
                        const timeSlotCell = e.target.closest('.time-slot-cell');
                        if (timeSlotCell) {
                            this.handleSlotClick(timeSlotCell, e);
                        }

                        const clearBtn = e.target.closest('.clear-class-btn');
                        if (clearBtn) {
                            e.stopPropagation(); // Prevent bubble
                            const classId = clearBtn.dataset.classId;
                            this.showConfirmDialog(
                                'Hapus Semua Jadwal'
                                , `Apakah Anda yakin ingin menghapus semua jadwal untuk kelas ini di hari ini?`
                                , () => this.clearClass(classId)
                            );
                        }
                    });

                    // Modal events
                    document.getElementById('saveQuickEditBtn').addEventListener('click', this.saveQuickEdit);
                    document.getElementById('deleteQuickEditBtn')?.addEventListener('click', this.deleteSchedule);
                    document.getElementById('cancelEditBtn').addEventListener('click', this.closeQuickEdit);
                    document.getElementById('closeQuickEditModal').addEventListener('click', this.closeQuickEdit);
                    document.getElementById('quickEditModalBackdrop').addEventListener('click', this.closeQuickEdit);

                    // Bulk edit
                    document.getElementById('applyBulkBtn').addEventListener('click', this.applyBulkEdit);

                    // Filter changes
                    let filterTimeout;
                    document.getElementById('classFilter').addEventListener('change', (e) => {
                         this.renderGrid(this.currentData); // Re-render instantly for client-side filter
                    });
                    
                    // Input mode change
                    document.getElementById('inputMode').addEventListener('change', (e) => {
                        const isBulk = e.target.value === 'bulk';
                        document.getElementById('bulkEditPanel').classList.toggle('hidden', !isBulk);
                        if(isBulk) {
                            this.showBulkSelectionGuide();
                        } else {
                            this.clearBulkSelection();
                        }
                    });

                    // Escape key
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            this.closeQuickEdit();
                        }
                    });
                }
                
                openQuickEdit(element) {
                    console.log('Opening Quick Edit...', element);
                    try {
                        const kelasId = element.dataset.kelas;
                        const day = element.dataset.day;
                        const timeSlot = element.dataset.slot;
                        const scheduleId = element.dataset.scheduleId;

                        const kelas = this.currentData.classes.find(k => k.id == kelasId);
                        const hari = this.currentData.days[day];

                        document.getElementById('modalKelasInfo').textContent = kelas ? kelas.nama_kelas : '-';
                        document.getElementById('modalHariInfo').textContent = hari || '-';
                        document.getElementById('modalJamInfo').textContent = `Jam ke-${timeSlot}`;

                        document.getElementById('edit_kelas_id').value = kelasId;
                        document.getElementById('edit_day_of_week').value = day;
                        document.getElementById('edit_time_slot').value = timeSlot;
                        document.getElementById('edit_schedule_id').value = scheduleId || '';

                        const modalTitle = document.getElementById('modalTitle');
                        const deleteBtn = document.getElementById('deleteQuickEditBtn');
                        
                        // Helper for Tom Select
                        const setTomSelectValue = (id, value) => {
                            const el = document.getElementById(id);
                            if (el && el.tomselect) {
                                el.tomselect.setValue(value);
                            } else {
                                console.warn(`TomSelect instance not found for #${id}`);
                                if(el) el.value = value;
                            }
                        };

                        const clearTomSelect = (id) => {
                            const el = document.getElementById(id);
                            if (el && el.tomselect) {
                                el.tomselect.clear();
                            } else {
                                if(el) el.value = '';
                            }
                        };

                        if (scheduleId) {
                            modalTitle.textContent = 'Edit Jadwal';
                            deleteBtn.classList.remove('hidden');
                            
                            const schedule = this.findScheduleById(scheduleId);
                            if (schedule) {
                                console.log('Editing Schedule:', schedule);
                                setTomSelectValue('edit_mata_pelajaran_id', schedule.mata_pelajaran_id);
                                setTomSelectValue('edit_teacher_id', schedule.teacher_id);

                                // Room info
                                const roomInfo = document.getElementById('edit_room_info');
                                if(schedule.room_name) {
                                    roomInfo.textContent = schedule.room_name;
                                    roomInfo.className = 'w-full pl-3 pr-8 sm:pr-10 py-2 text-sm border-gray-300 rounded-lg bg-green-50 text-green-700 font-medium';
                                }
                            }
                        } else {
                            modalTitle.textContent = 'Tambah Jadwal';
                            deleteBtn.classList.add('hidden');
                            
                            // Clear inputs
                            clearTomSelect('edit_mata_pelajaran_id');
                            clearTomSelect('edit_teacher_id');
                            
                            // Default room info
                            const roomInfo = document.getElementById('edit_room_info');
                            roomInfo.textContent = kelas && kelas.room ? kelas.room.name : 'Auto';
                            roomInfo.className = 'w-full pl-3 pr-8 sm:pr-10 py-2 text-sm border-gray-300 rounded-lg bg-gray-100 text-gray-500';
                        }

                        const modal = document.getElementById('quickEditModal');
                        const panel = document.getElementById('quickEditModalPanel');

                        modal.classList.remove('hidden');
                        panel.classList.remove('opacity-100', 'scale-100');
                        panel.classList.add('opacity-0', 'scale-95');

                        setTimeout(() => {
                            panel.classList.remove('opacity-0', 'scale-95');
                            panel.classList.add('opacity-100', 'scale-100');
                        }, 10);
                    } catch (error) {
                        console.error('Error in openQuickEdit:', error);
                        this.showError('Gagal membuka editor: ' + error.message);
                    }
                }
                
                // Add Delete Schedule
                async deleteSchedule() {
                    const scheduleId = document.getElementById('edit_schedule_id').value;
                    if (!scheduleId) return;

                    this.showConfirmDialog(
                        'Hapus Jadwal',
                        'Apakah Anda yakin ingin menghapus jadwal ini?',
                        async () => {
                            try {
                                const response = await fetch(`/admin/scheduling/manual/${scheduleId}`, { // Adjusted route if needed, standard resource destroy is DELETE /id
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                });
                                // Handle if route is actually delete-cell or similar
                                if(!response.ok) {
                                     // Fallback relative url if needed
                                } 
                                
                                const result = await response.json();
                                if (result.success) {
                                    this.showSuccess('Jadwal berhasil dihapus');
                                    this.closeQuickEdit();
                                    this.loadGrid();
                                } else {
                                    this.showError(result.message || 'Gagal menghapus jadwal');
                                }
                            } catch (error) {
                                console.error('Delete error', error);
                                this.showError('Gagal menghapus jadwal');
                            }
                        }
                    );
                }
                
                async saveQuickEdit() {
                    if (this.isSaving) return;
    
                    const subject = document.getElementById('edit_mata_pelajaran_id').value;
                    const teacher = document.getElementById('edit_teacher_id').value;
    
                    if (!subject || !teacher) {
                        this.showError('Mata pelajaran dan guru wajib diisi.');
                        return;
                    }
    
                    this.isSaving = true;
                    this.setSaveButtonLoading(true);
                    try {
                        const formData = new FormData(document.getElementById('quickEditForm'));
                        const scheduleId = formData.get('schedule_id');
                        const url = scheduleId ?
                            `/admin/scheduling/manual/${scheduleId}/quick-update` :
                            '/admin/scheduling/manual/quick-add';
                        const response = await fetch(url, {
                            method: scheduleId ? 'PUT' : 'POST'
                            , headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                , 'Content-Type': 'application/json'
                            }
                            , body: JSON.stringify(Object.fromEntries(formData))
                        });
                        const result = await response.json();
                        if (result.success) {
                            this.showSuccess(result.message || 'Jadwal berhasil disimpan');
                            this.loadGrid();
                            this.closeQuickEdit();
                        } else {
                            this.showError(result.errors ? result.errors.join(', ') : 'Gagal menyimpan jadwal');
                        }
                    } catch (error) {
                        console.error('Error saving schedule:', error);
                        this.showError('Terjadi kesalahan saat menyimpan');
                    } finally {
                        this.isSaving = false;
                        this.setSaveButtonLoading(false);
                    }
                }

                setSaveButtonLoading(loading) {
                    const buttonText = document.getElementById('saveButtonText');
                    const buttonLoading = document.getElementById('saveButtonLoading');
                    if (buttonText && buttonLoading) {
                        if (loading) {
                            buttonText.classList.add('hidden');
                            buttonLoading.classList.remove('hidden');
                        } else {
                            buttonText.classList.remove('hidden');
                            buttonLoading.classList.add('hidden');
                        }
                    }
                }

                closeQuickEdit() {
                    const panel = document.getElementById('quickEditModalPanel');
                    const modal = document.getElementById('quickEditModal');

                    panel.classList.remove('opacity-100', 'scale-100');
                    panel.classList.add('opacity-0', 'scale-95');

                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 300);
                }

                toggleBulkSelection(element) {
                    const cellId = `${element.dataset.kelas}-${element.dataset.day}-${element.dataset.slot}`;
                    if (element.classList.contains('bulk-selected')) {
                        element.classList.remove('bulk-selected', 'bg-blue-200', 'border-blue-400', 'ring-2', 'ring-blue-500');
                        this.selectedCells.delete(cellId);
                    } else {
                        element.classList.add('bulk-selected', 'bg-blue-200', 'border-blue-400', 'ring-2', 'ring-blue-500');
                        this.selectedCells.add(cellId);
                    }
                    this.updateBulkPanel();
                }

                async applyBulkEdit() {
                    const subjectId = document.getElementById('bulkSubject').value;
                    const teacherId = document.getElementById('bulkTeacher').value;
                    if (!subjectId || !teacherId) {
                        this.showError('Harap pilih mata pelajaran dan guru untuk bulk edit');
                        return;
                    }
                    this.showConfirmDialog(
                        'Apply Bulk Edit'
                        , `Apakah Anda yakin ingin mengapply setting ini ke ${this.selectedCells.size} slot yang dipilih?`
                        , async () => {
                            const updates = Array.from(this.selectedCells).map(cellId => {
                                const [kelasId, day, timeSlot] = cellId.split('-');
                                const kelas = this.currentData.classes.find(k => k.id == parseInt(kelasId));
                                return {
                                    kelas_id: parseInt(kelasId)
                                    , day_of_week: parseInt(day)
                                    , time_slot: parseInt(timeSlot)
                                    , mata_pelajaran_id: parseInt(subjectId)
                                    , teacher_id: parseInt(teacherId)
                                    , room_id: kelas ? kelas.room_id : null
                                };
                            });
                            try {
                                const response = await fetch('/admin/scheduling/manual/bulk-update', {
                                    method: 'POST'
                                    , headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        , 'Content-Type': 'application/json'
                                    }
                                    , body: JSON.stringify({
                                        updates
                                    })
                                });
                                const result = await response.json();
                                if (result.success) {
                                    this.showSuccess(`Berhasil update ${result.updated} jadwal`);
                                    this.clearBulkSelection();
                                    this.loadGrid();
                                } else {
                                    this.showError(result.errors ? result.errors.join(', ') : 'Gagal update bulk');
                                }
                            } catch (error) {
                                console.error('Error in bulk update:', error);
                                this.showError('Terjadi kesalahan saat update bulk');
                            }
                        }
                    );
                }

                applyFilters() {
                    const classFilter = document.getElementById('classFilter').value;
                    // Simply re-render, renderGrid handles filtering logic
                    this.renderGrid(this.currentData);
                }

                clearBulkSelection() {
                    document.querySelectorAll('.bulk-selected').forEach(el => {
                        el.classList.remove('bulk-selected', 'bg-blue-200', 'border-blue-400', 'ring-2', 'ring-blue-500');
                    });
                    this.selectedCells.clear();
                    this.updateBulkPanel();
                }

                showBulkSelectionGuide() {
                    this.showSuccess('Mode Bulk Input aktif. Klik multiple slot untuk memilih, lalu apply settings di panel bawah.');
                }

                // MODERN TOAST NOTIFICATIONS
                showSuccess(message) {
                    this.showToast(message, 'success');
                }

                showError(message) {
                    this.showToast(message, 'error');
                }

                showToast(message, type = 'info') {
                    const toastContainer = document.getElementById('toastContainer');
                    const toastId = 'toast-' + Date.now();

                    const icons = {
                        success: `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`
                        , error: `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`
                        , info: `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
                    };

                    const colors = {
                        success: 'bg-green-50 border-green-200 text-green-800'
                        , error: 'bg-red-50 border-red-200 text-red-800'
                        , info: 'bg-blue-50 border-blue-200 text-blue-800'
                    };

                    const toast = document.createElement('div');
                    toast.id = toastId;
                    toast.className = `p-3 rounded-lg border shadow-lg transform transition-all duration-300 ${colors[type]} opacity-0 translate-x-full text-sm`;
                    toast.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            ${icons[type]}
                        </div>
                        <div class="ml-2 flex-1">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <button type="button" class="ml-2 -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex h-6 w-6 hover:bg-gray-100 transition-colors duration-200" onclick="document.getElementById('${toastId}').remove()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;

                    toastContainer.appendChild(toast);

                    setTimeout(() => {
                        toast.classList.remove('opacity-0', 'translate-x-full');
                        toast.classList.add('opacity-100', 'translate-x-0');
                    }, 10);

                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.classList.remove('opacity-100', 'translate-x-0');
                            toast.classList.add('opacity-0', 'translate-x-full');
                            setTimeout(() => {
                                if (toast.parentNode) {
                                    toast.remove();
                                }
                            }, 300);
                        }
                    }, 4000);
                }

                // CONFIRM DIALOG MODERN
                showConfirmDialog(title, message, onConfirm) {
                    const dialogId = 'confirm-dialog-' + Date.now();
                    const dialog = document.createElement('div');
                    dialog.id = dialogId;
                    dialog.className = 'fixed inset-0 z-50 overflow-y-auto';
                    dialog.innerHTML = `
                    <div class="flex items-center justify-center min-h-screen px-3 sm:px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 backdrop-blur-sm"></div>
                        <div class="relative inline-block w-full max-w-md px-3 sm:px-4 pt-4 pb-3 sm:pt-5 sm:pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl sm:rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:p-6">
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-yellow-100 rounded-lg">
                                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 sm:ml-4">
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900">${title}</h3>
                                    <p class="text-xs sm:text-sm text-gray-500">${message}</p>
                                </div>
                            </div>
                            <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                                <button type="button" class="w-full sm:w-auto px-3 sm:px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200" id="cancelBtn">
                                    Batal
                                </button>
                                <button type="button" class="w-full sm:w-auto px-3 sm:px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 transition-all duration-200" id="confirmBtn">
                                    Ya, Lanjutkan
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                    document.body.appendChild(dialog);

                    const cancelBtn = dialog.querySelector('#cancelBtn');
                    const confirmBtn = dialog.querySelector('#confirmBtn');

                    const closeDialog = () => {
                        dialog.classList.add('opacity-0');
                        setTimeout(() => {
                            if (dialog.parentNode) dialog.remove();
                        }, 300);
                    };

                    cancelBtn.addEventListener('click', closeDialog);
                    confirmBtn.addEventListener('click', () => {
                        closeDialog();
                        onConfirm();
                    });

                    dialog.addEventListener('click', (e) => {
                        if (e.target === dialog) closeDialog();
                    });

                    const escapeHandler = (e) => {
                        if (e.key === 'Escape') {
                            closeDialog();
                            document.removeEventListener('keydown', escapeHandler);
                        }
                    };
                    document.addEventListener('keydown', escapeHandler);
                }

                truncateText(text, maxLength) {
                    if (!text) return '';
                    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
                }

                findSchedule(schedules, kelasId, day, timeSlot) {
                    if (!schedules) return null;
                    return schedules.find(s =>
                        s.kelas_id == kelasId &&
                        s.day_of_week == day &&
                        s.time_slot == timeSlot
                    ) || null;
                }

                findScheduleById(scheduleId) {
                    return this.currentData.schedules.find(s => s.id == scheduleId) || null;
                }

                updateStats(data) {
                    const totalSlots = data.classes.length * 6 * 7;
                    const filledSlots = data.schedules ? data.schedules.length : 0;
                    const conflictSlots = data.schedules ? data.schedules.filter(s => s.has_conflict).length : 0;
                    document.getElementById('totalSlots').textContent = totalSlots;
                    document.getElementById('filledSlots').textContent = filledSlots;
                    document.getElementById('emptySlots').textContent = totalSlots - filledSlots;
                    document.getElementById('conflictSlots').textContent = conflictSlots;
                }

                showLoading() {
                    document.getElementById('loadingIndicator').classList.remove('hidden');
                }

                hideLoading() {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                }

                updateBulkPanel() {
                    const count = document.getElementById('selectedCellsCount');
                    if(count) count.textContent = `${this.selectedCells.size} slot terpilih`;
                }

                async clearClass(classId) {
                    this.showConfirmDialog(
                        'Hapus Semua Jadwal'
                        , `Apakah Anda yakin ingin menghapus semua jadwal untuk kelas ini?`
                        , async () => {
                            try {
                                const response = await fetch(`/admin/scheduling/manual/clear-class/${classId}`, {
                                    method: 'DELETE'
                                    , headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                });
                                const result = await response.json();
                                if (result.success) {
                                    this.showSuccess('Berhasil menghapus semua jadwal untuk kelas');
                                    this.loadGrid();
                                } else {
                                    this.showError(result.message || 'Gagal menghapus jadwal');
                                }
                            } catch (error) {
                                console.error('Error clearing class:', error);
                                this.showError('Terjadi kesalahan saat menghapus jadwal');
                            }
                        }
                    );
                }
            }    

            // Instantiate Global Grid
            document.addEventListener('DOMContentLoaded', () => {
                console.log('Initializing ScheduleGrid...');
                window.scheduleGrid = new ScheduleGrid();
                
                // Initialize Tom Selects
                document.querySelectorAll('.tom-select-subject, .tom-select-teacher').forEach(el => {
                    if(typeof TomSelect !== 'undefined') {
                         new TomSelect(el, {
                            create: false,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });
                    }
                });
            });
    </script>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <!-- Custom Tom Select Styles -->
    <style>
        /* Tom Select Custom Styles */
        .ts-control {
            @apply border-gray-300 rounded-lg shadow-sm focus: ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200;
        }

        .ts-wrapper.single .ts-control,
        .ts-wrapper.multi .ts-control {
            @apply py-2 px-3 text-sm sm: text-base;
        }

        .ts-wrapper.single .ts-control:focus-within,
        .ts-wrapper.multi .ts-control:focus-within {
            @apply border-red-500 ring-2 ring-red-500 ring-opacity-20;
        }

        .ts-dropdown {
            @apply border-gray-300 rounded-lg shadow-lg mt-1 z-50;
        }

        .ts-dropdown .option:hover,
        .ts-dropdown .option:focus,
        .ts-dropdown .active {
            @apply bg-red-50 text-red-800;
        }

        .ts-dropdown .option.selected {
            @apply bg-red-100 text-red-900 font-medium;
        }

        .ts-wrapper.multi .ts-control>div {
            @apply bg-red-100 text-red-800 rounded px-2 py-1 text-sm font-medium;
        }

        .ts-wrapper.multi .ts-control>div.active {
            @apply bg-red-200;
        }

        .ts-wrapper.single .ts-control .item,
        .ts-wrapper.multi .ts-control .item {
            @apply text-sm sm: text-base;
        }

        /* Fixed width for bulk edit inputs */
        .bulk-select-container .ts-wrapper .ts-control {
            @apply min-w-[180px] md: min-w-[208px];
        }

        /* Info container styling */
        .bulk-info-container .ts-control {
            @apply min-h-[42px] md: min-h-[46px];
        }

        /* Placeholder styling */
        .ts-control .placeholder {
            @apply text-gray-500 truncate;
        }

        /* Ensure consistent height */
        .ts-wrapper.single .ts-control,
        .ts-wrapper.multi .ts-control {
            @apply min-h-[42px] md: min-h-[46px];
        }

        /* Dropdown input styling */
        .ts-dropdown .dropdown-input {
            @apply py-2 px-3 text-sm border-b border-gray-200 focus: outline-none focus:ring-0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            .ts-wrapper.single .ts-control,
            .ts-wrapper.multi .ts-control {
                @apply py-1.5 px-2 text-sm;
            }

            .ts-wrapper.multi .ts-control>div {
                @apply px-1.5 py-0.5 text-xs;
            }

            /* Fixed width for mobile */
            .bulk-select-container .ts-wrapper .ts-control {
                @apply min-w-[100px] md: min-w-[120px];
            }

            .ts-wrapper.single .ts-control,
            .ts-wrapper.multi .ts-control {
                @apply min-h-[38px] md: min-h-[42px];
            }
        }

        /* Extra small devices */
        @media (max-width: 480px) {
            .bulk-select-container .ts-wrapper .ts-control {
                @apply min-w-[90px];
            }

            .ts-wrapper.single .ts-control,
            .ts-wrapper.multi .ts-control {
                @apply min-h-[36px] py-1 px-1.5 text-xs;
            }
        }

        /* Medium devices */
        @media (min-width: 768px) and (max-width: 1024px) {
            .bulk-select-container .ts-wrapper .ts-control {
                @apply min-w-[140px];
            }
        }

        /* Large devices */
        @media (min-width: 1024px) {
            .bulk-select-container .ts-wrapper .ts-control {
                @apply min-w-[208px];
            }
        }

        /* Desktop specific adjustments */
        @media (min-width: 1280px) {
            .bulk-select-container .ts-wrapper .ts-control {
                @apply min-w-[220px];
            }
        }

    </style>

    @endpush
</x-app-layout>
