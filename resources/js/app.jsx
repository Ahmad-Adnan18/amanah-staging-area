import './bootstrap';
import Alpine from 'alpinejs';
import { createRoot } from 'react-dom/client';
import GlassIcons from './components/GlassIcons';
import { App } from '@capacitor/app';
// --- 1. TAMBAHAN IMPORT BIOMETRIC & PREFERENCES ---
import { NativeBiometric } from '@capgo/capacitor-native-biometric';
import { Preferences } from '@capacitor/preferences';
import { LocalNotifications } from '@capacitor/local-notifications';

window.Alpine = Alpine;
Alpine.start();

// =================================================================
// LOGIKA BIOMETRIC LOGIN (SIDIK JARI / WAJAH)
// =================================================================
const CREDENTIAL_KEY = 'user_credentials';

// Cek apakah HP support Biometric?
window.checkBiometricSupport = async () => {
    try {
        const result = await NativeBiometric.isAvailable();
        return result.isAvailable;
    } catch (error) {
        return false;
    }
};

// Simpan Credential (Dipanggil manual saat login sukses)
window.saveCredentials = async (email, password) => {
    const data = JSON.stringify({ email, password });
    await Preferences.set({
        key: CREDENTIAL_KEY,
        value: data,
    });
};

// Hapus Credential (Dipanggil saat Logout atau Login Gagal)
window.clearCredentials = async () => {
    await Preferences.remove({ key: CREDENTIAL_KEY });
};

// Eksekusi Login Biometric
window.performBiometricLogin = async () => {
    try {
        // 1. Ambil data dari HP
        const { value } = await Preferences.get({ key: CREDENTIAL_KEY });
        
        if (!value) {
            alert('Data login kosong. Silakan login manual dulu.');
            return;
        }

        const credentials = JSON.parse(value);

        // Validasi
        if (!credentials.email || !credentials.password) {
            alert('Data rusak. Login manual dulu.');
            return;
        }

        // 2. Scan Sidik Jari
        await NativeBiometric.verifyIdentity({
            reason: "Login",
            title: "Login Amanah",
            subtitle: "Scan jari",
            description: " "
        });

        const originalForm = document.getElementById('login-form');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        if (!originalForm || !emailInput || !passwordInput) {
            alert("Error: Form login tidak lengkap.");
            return;
        }

        emailInput.value = credentials.email;
        passwordInput.value = credentials.password;

        // Pastikan Alpine/turunan Livewire menangkap perubahan
        emailInput.dispatchEvent(new Event('input', { bubbles: true }));
        passwordInput.dispatchEvent(new Event('input', { bubbles: true }));

        const btnContainer = document.getElementById('biometric-container');
        if (btnContainer) {
            btnContainer.innerHTML = '<p class="text-center text-red-600 font-bold">Sedang Masuk...</p>';
        }

        setTimeout(() => {
            if (typeof originalForm.requestSubmit === 'function') {
                originalForm.requestSubmit();
            } else {
                originalForm.submit();
            }
        }, 100);
    } catch (error) {
        alert("Biometric Error: " + error.message);
    }
};


// =================================================================
// LOGIKA CAPACITOR (ANDROID BACK BUTTON)
// =================================================================
App.addListener('backButton', ({ canGoBack }) => {
    const currentUrl = window.location.pathname;
    const exitPages = ['/', '/login', '/dashboard'];

    if (exitPages.includes(currentUrl)) {
        App.exitApp();
    } else {
        if (document.referrer !== "" && window.history.length > 1) {
            window.history.back();
        } else {
            App.exitApp();
        }
    }
});

// =================================================================
// LOGIKA REACT (GLASS ICONS) - TETAP SAMA
// =================================================================
const glassRoots = new Map();
const renderGlassIcons = (container) => {
    const rawItems = container.dataset.glassItems ?? '[]';
    let parsedItems = [];
    try {
        parsedItems = JSON.parse(rawItems);
    } catch (error) {
        console.error('Gagal parsing data GlassIcons:', error);
        return;
    }
    if (!Array.isArray(parsedItems) || parsedItems.length === 0) {
        if (glassRoots.has(container)) {
            glassRoots.get(container).unmount();
            glassRoots.delete(container);
        }
        return;
    }
    if (!glassRoots.has(container)) {
        glassRoots.set(container, createRoot(container));
    }
    const extraClass = container.dataset.extraClass ?? '';
    glassRoots.get(container)?.render(<GlassIcons items={parsedItems} className={extraClass} />);
};

const initGlassIcons = () => {
    const containers = document.querySelectorAll('[data-glass-items]');
    if (!containers.length) return;
    containers.forEach(renderGlassIcons);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGlassIcons);
} else {
    initGlassIcons();
}

if (import.meta.hot) {
    import.meta.hot.dispose(() => {
        glassRoots.forEach((root) => root.unmount());
        glassRoots.clear();
    });
}

// =================================================================
// KODE PWA (Service Worker Cleaner & Install Prompt)
// =================================================================
document.addEventListener('DOMContentLoaded', () => {
    // Bersihkan SW lama agar tidak stuck cache
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations().then(function(registrations) {
            for(let registration of registrations) {
                registration.unregister();
            }
        });
    }

    // Logic Install Button PWA (Tetap Sama)
    let deferredPrompt = null;
    const installButton = document.getElementById('install-pwa-button');
    function toggleInstallButton(show) {
        if (installButton) {
            installButton.style.display = show ? 'block' : 'none';
            if(show) installButton.classList.remove('hidden');
            else installButton.classList.add('hidden');
        }
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        if (installButton && !window.matchMedia('(display-mode: standalone)').matches) {
            toggleInstallButton(true);
        }
    });

    if (installButton) {
        installButton.addEventListener('click', async () => {
            if (deferredPrompt) {
                toggleInstallButton(false);
                try {
                    await deferredPrompt.prompt();
                    deferredPrompt = null;
                } catch (error) {
                    if (error.name === 'NotAllowedError') toggleInstallButton(true);
                }
            }
        });
    }

    window.addEventListener('appinstalled', () => {
        toggleInstallButton(false);
        deferredPrompt = null;
    });
    
    if (window.matchMedia('(display-mode: standalone)').matches) {
        toggleInstallButton(false);
    }
});

// =================================================================
// LOGIKA PENJADWALAN NOTIFIKASI (JADWAL MENGAJAR) - REVISI
// =================================================================
document.addEventListener('DOMContentLoaded', async () => {
    // Kita cek apakah variable window.teacherSchedules sudah didefinisikan di Blade?
    // Walaupun kosong ( [] ), kita tetap harus jalankan untuk proses pembersihan.
    if (typeof window.teacherSchedules !== 'undefined') {
        console.log("Memproses sinkronisasi notifikasi...");
        await scheduleClasses(window.teacherSchedules);
    }
});

const scheduleClasses = async (schedules) => {
    try {
        // 1. Cek & Minta Izin
        let permStatus = await LocalNotifications.checkPermissions();
        if (permStatus.display !== 'granted') {
            permStatus = await LocalNotifications.requestPermissions();
        }
        if (permStatus.display !== 'granted') return;

        // 2. Buat Channel (Wajib)
        await LocalNotifications.createChannel({
            id: 'jadwal_mengajar',
            name: 'Jadwal Mengajar',
            description: 'Pengingat masuk kelas',
            importance: 5,
            visibility: 1,
            sound: 'default',
            vibration: true,
        });

        // 3. MEMBERSIHKAN NOTIFIKASI LAMA (PENTING!)
        // Ini wajib jalan, mau ada jadwal atau tidak.
        // Supaya kalau hari ini libur, notifikasi sisa kemarin hilang.
        const pending = await LocalNotifications.getPending();
        if (pending.notifications.length > 0) {
            await LocalNotifications.cancel(pending);
            console.log("Membersihkan notifikasi lama...");
        }

        // 4. CEK APAKAH ADA JADWAL BARU?
        // Kalau kosong (Hari Libur / Tidak ada jam), berhenti di sini.
        if (!schedules || schedules.length === 0) {
            console.log("Hari ini tidak ada jadwal. Tidak ada notifikasi baru.");
            return;
        }

        // 5. Jika Ada Jadwal, Baru Kita Proses
        const notificationsList = [];
        const now = new Date();

        schedules.forEach((item) => {
            const [hours, minutes] = item.time.split(':');
            
            let scheduleDate = new Date();
            scheduleDate.setHours(parseInt(hours), parseInt(minutes), 0, 0);

            // Logic: Jika jam sudah lewat, jadwalkan untuk besok
            if (scheduleDate < now) {
                scheduleDate.setDate(scheduleDate.getDate() + 1);
            }

            notificationsList.push({
                id: parseInt(item.id),
                title: item.title,
                body: item.body,
                schedule: { at: scheduleDate, allowWhileIdle: true },
                channelId: 'jadwal_mengajar',
                sound: 'default',
            });
        });

        // 6. Daftarkan Notifikasi Baru
        if (notificationsList.length > 0) {
            await LocalNotifications.schedule({ notifications: notificationsList });
            console.log(`Sukses menjadwalkan ${notificationsList.length} notifikasi baru.`);
        }

    } catch (error) {
        console.error("Gagal menjadwalkan notifikasi:", error);
    }
};