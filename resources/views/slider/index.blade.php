<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Slider') }}
        </h2>
    </x-slot>

    <!-- Pastikan meta CSRF token ada di bagian head atau sebelum script utama -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Notifikasi Sukses atau Error -->
                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif
                @if (session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif
                @if ($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-2xl shadow-sm" role="alert">
                    <p class="font-bold">Terjadi Kesalahan</p>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Placeholder untuk notifikasi dari URL parameter (akan ditampilkan oleh JavaScript) -->
                <div id="url-notification-placeholder"></div>

                <!-- Bungkus semua konten manajemen slider ke dalam x-data -->
                <div x-data="sliderManagement()" x-init="loadSliderItems(); checkUrlNotifications();">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Item Slider</h3>
                        <!-- Tombol ini sekarang ada di dalam scope x-data -->
                        <button @click="showCreateFormToggle()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Item Slider
                        </button>
                    </div>

                    <!-- Loading indicator -->
                    <div x-show="loading" class="flex justify-center py-10">
                        <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-red-600"></div>
                        <span class="ml-3 text-gray-700">Memuat item slider...</span>
                    </div>

                    <!-- Items list -->
                    <div x-show="!loading && items.length > 0" class="space-y-4">
                        <template x-for="item in items" :key="item.id">
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <template x-if="item.type === 'image'">
                                                <img :src="item.file_url" alt="Preview" class="h-16 w-16 object-cover rounded">
                                            </template>
                                            <template x-if="item.type === 'video'">
                                                <div class="h-16 w-16 flex items-center justify-center bg-gray-200 rounded">
                                                    <svg class="h-8 w-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            </template>
                                            <template x-if="item.type === 'external'">
                                                <div class="h-16 w-16 flex items-center justify-center bg-gray-200 rounded">
                                                    <svg class="h-8 w-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900" x-text="item.title"></h4>
                                            <p class="text-sm text-gray-500" x-text="item.type.toUpperCase()"></p>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                                    <span x-text="item.is_active ? 'Aktif' : 'Tidak Aktif'"></span>
                                                </span>
                                                <span class="text-xs text-gray-500" x-text="'Urutan: ' + item.order"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button @click="editItem(item)" class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button @click="deleteItem(item.id)" class="text-red-600 hover:text-red-900" title="Hapus">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- No items message -->
                    <div x-show="!loading && items.length === 0" class="text-center py-10 text-gray-500">
                        Belum ada item slider. Klik "Tambah Item Slider" untuk menambahkan.
                    </div>

                    <!-- Create/Edit Modal -->
                    <div x-show="showCreateForm || showEditForm" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center" x-cloak>
                        <div class="relative top-0 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
                            <div class="mt-3">
                                <div class="flex justify-between items-center pb-3 border-b">
                                    <h3 class="text-lg font-medium text-gray-900" x-text="showCreateForm ? 'Tambah Item Slider' : 'Edit Item Slider'"></h3>
                                    <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <form @submit.prevent="showCreateForm ? createItem() : updateItem()" class="mt-4 space-y-4">
                                    <div>
                                        <label for="title" class="block text-sm font-medium text-gray-700">Judul</label>
                                        <input type="text" id="title" x-model="formData.title" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                    </div>

                                    <div>
                                        <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                                        <select id="type" x-model="formData.type" @change="changeType" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                            <option value="image">Gambar</option>
                                            <option value="video">Video</option>
                                            <option value="external">URL Eksternal</option>
                                        </select>
                                    </div>

                                    <template x-if="formData.type !== 'external'">
                                        <div>
                                            <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                                            <input type="file" id="file" @change="handleFileUpload" :accept="formData.type === 'image' ? 'image/*' : 'video/*'" class="mt-1 block w-full">
                                            <p class="mt-1 text-sm text-gray-500">Ukuran maksimal: 10MB</p>
                                            <template x-if="formData.filePreview">
                                                <div class="mt-2" x-show="formData.type === 'image'">
                                                    <img :src="formData.filePreview" alt="Preview" class="h-32 w-32 object-cover rounded">
                                                </div>
                                            </template>
                                            <template x-if="formData.id && !formData.file && formData.file_url">
                                                <div class="mt-2 text-sm text-gray-600">File saat ini: <a :href="formData.file_url" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></div>
                                            </template>
                                        </div>
                                    </template>

                                    <template x-if="formData.type === 'external'">
                                        <div>
                                            <label for="external_url" class="block text-sm font-medium text-gray-700">URL Eksternal</label>
                                            <input type="url" id="external_url" x-model="formData.external_url" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500" placeholder="https://www.youtube.com/watch?v=...">
                                            <p class="mt-1 text-sm text-gray-500">Contoh: URL YouTube, Instagram, atau media lainnya</p>
                                        </div>
                                    </template>

                                    <div>
                                        <label for="order" class="block text-sm font-medium text-gray-700">Urutan</label>
                                        <input type="number" id="order" x-model.number="formData.order" min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" id="is_active" x-model="formData.is_active" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Aktif</label>
                                    </div>

                                    <div class="flex justify-end space-x-3 pt-4">
                                        <button type="button" @click="closeModal" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Batal
                                        </button>
                                        <button type="submit" :disabled="submitting" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <span x-show="!submitting" x-text="showCreateForm ? 'Simpan' : 'Update'"></span>
                                            <span x-show="submitting">Memproses...</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sliderManagement() {
            return {
                items: []
                , loading: false
                , showCreateForm: false
                , showEditForm: false
                , submitting: false,
                // Tambahkan 'file_url' ke formData untuk menyimpan URL file saat ini (untuk edit)
                formData: {
                    id: null
                    , title: ''
                    , type: 'image'
                    , file: null, // File objek untuk diupload
                    filePreview: null, // URL untuk pratinjau gambar baru
                    file_url: null, // URL file yang sudah ada dari database (untuk edit)
                    external_url: ''
                    , is_active: true
                    , order: 0
                },

                async loadSliderItems() {
                    this.loading = true;
                    try {
                        const response = await fetch('/slider/data');
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        const data = await response.json();
                        this.items = data.data || [];
                    } catch (error) {
                        console.error('Error loading slider items:', error);
                        // Gagal memuat data biasanya karena error jaringan atau server, 
                        // untuk ini kita tetap gunakan alert karena tidak bisa kirim ke session
                        alert('Gagal memuat item slider. Silakan coba lagi nanti.');
                    } finally {
                        this.loading = false;
                    }
                },

                async createItem() {
                    this.submitting = true;
                    const formData = new FormData();
                    formData.append('title', this.formData.title);
                    formData.append('type', this.formData.type);

                    if (this.formData.type === 'external') {
                        formData.append('external_url', this.formData.external_url);
                    } else if (this.formData.file) {
                        // Hanya append file jika ada file baru yang dipilih
                        formData.append('file', this.formData.file);
                    }

                    formData.append('is_active', this.formData.is_active ? 1 : 0);
                    formData.append('order', this.formData.order);

                    try {
                        const response = await fetch('/slider/store', {
                            method: 'POST'
                            , body: formData
                            , headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        // Cek jika respons berupa JSON sebelum mencoba menguraikannya
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const result = await response.json();

                            if (response.ok && result.success) {
                                // Redirect ke halaman slider dengan parameter sukses
                                window.location.href = '/slider?success=' + encodeURIComponent('Item slider berhasil ditambahkan!');
                            } else {
                                const errorMessage = result.message || `Terjadi kesalahan saat menyimpan data. Status: ${response.status}`;
                                alert('Gagal menyimpan data: ' + errorMessage);
                                console.error('API Error:', result);
                            }
                        } else {
                            // Jika bukan JSON, kemungkinan besar HTML (misalnya error page atau redirect)
                            const text = await response.text();
                            console.error('Server response (HTML):', text);
                            alert('Terjadi kesalahan server. Silakan coba lagi nanti.');
                        }
                    } catch (error) {
                        console.error('Error creating slider item:', error);
                        alert('Terjadi kesalahan saat menyimpan data. Periksa koneksi Anda.');
                    } finally {
                        this.submitting = false;
                    }
                },

                changeType() {
                    // Reset file dan URL ketika tipe berubah
                    if (this.formData.type === 'external') {
                        this.formData.file = null;
                        this.formData.filePreview = null;
                    } else {
                        this.formData.external_url = '';
                    }
                },

                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.formData.file = file; // Simpan file objek
                        // Buat pratinjau untuk gambar
                        if (this.formData.type === 'image') {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.formData.filePreview = e.target.result; // URL untuk pratinjau
                            };
                            reader.readAsDataURL(file);
                        } else {
                            this.formData.filePreview = null; // Reset pratinjau jika bukan gambar
                        }
                    } else {
                        this.formData.file = null;
                        this.formData.filePreview = null;
                    }
                },

                editItem(item) {
                    // Isi formData dengan data item yang akan diedit
                    this.formData = {
                        id: item.id
                        , title: item.title
                        , type: item.type
                        , file: null, // Reset file input saat edit, pengguna harus memilih ulang jika ingin mengubah
                        filePreview: item.type === 'image' ? item.file_url : null, // Tampilkan pratinjau file yang sudah ada
                        file_url: item.file_url, // Simpan URL file yang sudah ada
                        external_url: item.external_url || ''
                        , is_active: !!item.is_active, // Konversi ke boolean
                        order: item.order
                    };
                    this.showCreateForm = false;
                    this.showEditForm = true;
                },

                async updateItem() {
                    this.submitting = true;
                    const formData = new FormData();
                    formData.append('title', this.formData.title);
                    formData.append('type', this.formData.type);

                    if (this.formData.type === 'external') {
                        formData.append('external_url', this.formData.external_url);
                    } else if (this.formData.file) {
                        // Hanya append file jika ada file baru yang dipilih
                        formData.append('file', this.formData.file);
                    }

                    formData.append('is_active', this.formData.is_active ? 1 : 0);
                    formData.append('order', this.formData.order);
                    // Penting untuk Laravel: _method=PUT untuk HTTP PUT request
                    formData.append('_method', 'PUT'); // Laravel meniru PUT dengan POST + _method
                    // CSRF token sudah di set di header fetch request

                    try {
                        const response = await fetch(`/slider/${this.formData.id}`, {
                            method: 'POST', // Menggunakan POST karena FormData tidak mendukung PUT
                            body: formData
                            , headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        // Cek jika respons berupa JSON sebelum mencoba menguraikannya
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const result = await response.json();

                            if (response.ok && result.success) {
                                // Redirect ke halaman slider dengan parameter sukses
                                window.location.href = '/slider?success=' + encodeURIComponent('Item slider berhasil diperbarui!');
                            } else {
                                const errorMessage = result.message || `Terjadi kesalahan saat mengupdate data. Status: ${response.status}`;
                                alert('Gagal mengupdate data: ' + errorMessage);
                                console.error('API Error:', result);
                            }
                        } else {
                            // Jika bukan JSON, kemungkinan besar HTML (misalnya error page atau redirect)
                            const text = await response.text();
                            console.error('Server response (HTML):', text);
                            alert('Terjadi kesalahan server. Silakan coba lagi nanti.');
                        }
                    } catch (error) {
                        console.error('Error updating slider item:', error);
                        alert('Terjadi kesalahan saat mengupdate data. Periksa koneksi Anda.');
                    } finally {
                        this.submitting = false;
                    }
                },

                async deleteItem(id) {
                    if (!confirm('Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.')) {
                        return;
                    }

                    try {
                        const response = await fetch(`/slider/${id}`, {
                            method: 'DELETE'
                            , headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        // Cek jika respons berupa JSON sebelum mencoba menguraikannya
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const result = await response.json();

                            if (response.ok && result.success) {
                                // Redirect ke halaman slider dengan parameter sukses
                                window.location.href = '/slider?success=' + encodeURIComponent('Item slider berhasil dihapus!');
                            } else {
                                const errorMessage = result.message || `Terjadi kesalahan saat menghapus data. Status: ${response.status}`;
                                alert('Gagal menghapus data: ' + errorMessage);
                                console.error('API Error:', result);
                            }
                        } else {
                            // Jika bukan JSON, kemungkinan besar HTML (misalnya error page atau redirect)
                            const text = await response.text();
                            console.error('Server response (HTML):', text);
                            alert('Terjadi kesalahan server. Silakan coba lagi nanti.');
                        }
                    } catch (error) {
                        console.error('Error deleting slider item:', error);
                        alert('Terjadi kesalahan saat menghapus data. Periksa koneksi Anda.');
                    }
                },

                closeModal() {
                    this.showCreateForm = false;
                    this.showEditForm = false;
                    this.resetForm();
                },

                resetForm() {
                    this.formData = {
                        id: null
                        , title: ''
                        , type: 'image'
                        , file: null
                        , filePreview: null
                        , file_url: null
                        , external_url: ''
                        , is_active: true
                        , order: 0
                    };
                },

                showCreateFormToggle() {
                    this.showCreateForm = true;
                    this.showEditForm = false;
                    this.resetForm(); // Pastikan form bersih saat ingin membuat item baru
                },
                
                // Fungsi untuk mengecek parameter URL dan menampilkan notifikasi
                checkUrlNotifications() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const success = urlParams.get('success');
                    const error = urlParams.get('error');
                    
                    if (success) {
                        this.showNotification(success, 'success');
                        // Hapus parameter dari URL agar tidak muncul lagi setelah refresh
                        this.removeUrlParameter(['success']);
                    } else if (error) {
                        this.showNotification(error, 'error');
                        // Hapus parameter dari URL agar tidak muncul lagi setelah refresh
                        this.removeUrlParameter(['error']);
                    }
                },
                
                // Fungsi untuk menampilkan notifikasi dalam format yang sesuai dengan komponen x-alert
                showNotification(message, type) {
                    const notificationPlaceholder = document.getElementById('url-notification-placeholder');
                    if (!notificationPlaceholder) return;
                    
                    // Buat elemen notifikasi sesuai dengan format x-alert
                    const alertDiv = document.createElement('div');
                    alertDiv.setAttribute('x-data', '{ show: true }');
                    alertDiv.setAttribute('x-show', 'show');
                    alertDiv.setAttribute('x-transition:enter', 'transition ease-out duration-300');
                    alertDiv.setAttribute('x-transition:enter-start', 'opacity-0 transform translate-y-1');
                    alertDiv.setAttribute('x-transition:enter-end', 'opacity-100 transform translate-y-0');
                    alertDiv.setAttribute('x-transition:leave', 'transition ease-in duration-200');
                    alertDiv.setAttribute('x-transition:leave-start', 'opacity-100 transform scale-100');
                    alertDiv.setAttribute('x-transition:leave-end', 'opacity-0 transform scale-95');
                    
                    // Tentukan kelas CSS berdasarkan tipe notifikasi
                    let bgClass, borderClass, textClass, icon;
                    if (type === 'success') {
                        bgClass = 'bg-green-50';
                        borderClass = 'border-green-200';
                        textClass = 'text-green-800';
                        icon = `
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        `;
                    } else { // error
                        bgClass = 'bg-red-50';
                        borderClass = 'border-red-200';
                        textClass = 'text-red-800';
                        icon = `
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        `;
                    }
                    
                    alertDiv.className = `${bgClass} ${borderClass} ${textClass} px-4 py-3 rounded-xl shadow-sm border flex items-start transition-all duration-300`;
                    
                    alertDiv.innerHTML = `
                        <div class="flex-shrink-0 mr-3">
                            ${icon}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold ${textClass}">${type === 'success' ? 'Berhasil' : 'Error'}</h3>
                            <div class="mt-1 text-sm ${textClass}">${message}</div>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition-colors duration-200">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                    
                    notificationPlaceholder.appendChild(alertDiv);
                    
                    // Inisialisasi Alpine.js untuk elemen baru
                    if (window.Alpine) {
                        window.Alpine.initTree(alertDiv);
                    }
                },
                
                // Fungsi untuk menghapus parameter dari URL
                removeUrlParameter(params) {
                    const url = new URL(window.location);
                    params.forEach(param => {
                        url.searchParams.delete(param);
                    });
                    
                    // Ganti URL tanpa parameter
                    window.history.replaceState({}, document.title, url);
                }
            }
        }

    </script>
</x-app-layout>
