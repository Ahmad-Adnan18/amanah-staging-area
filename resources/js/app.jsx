import './bootstrap';
import Alpine from 'alpinejs';
import { createRoot } from 'react-dom/client';
import GlassIcons from './components/GlassIcons';
import { App } from '@capacitor/app';
// import { Browser } from '@capacitor/browser'; // Removed logic
// --- 1. TAMBAHAN IMPORT BIOMETRIC & PREFERENCES ---
import { NativeBiometric } from '@capgo/capacitor-native-biometric';
import { Preferences } from '@capacitor/preferences';
import { LocalNotifications } from '@capacitor/local-notifications';
import { PushNotifications } from '@capacitor/push-notifications';

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
        navigator.serviceWorker.getRegistrations().then(function (registrations) {
            for (let registration of registrations) {
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
            if (show) installButton.classList.remove('hidden');
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
// LOGIKA PENJADWALAN NOTIFIKASI DIHAPUS (DIGANTI SERVER-SIDE)
// =================================================================
// Code removal: scheduleClasses function and invocation removed.

// =================================================================
// LOGIKA PUSH NOTIFICATION (FCM) - FIXED & CLEAN
// =================================================================
// =================================================================
// LOGIKA PUSH NOTIFICATION (FCM) - PRODUCTION READY
// =================================================================
// --- 1. TAMBAHAN IMPORT FIREBASE (WEB) ---
import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";

// ... (Import lain tetap)

// =================================================================
// KONFIGURASI FIREBASE WEB (DARI USER)
// =================================================================
const firebaseConfig = {
    apiKey: "AIzaSyA0jXdEMSnmv4SQvxop17SqLKt4m8ab_UE",
    authDomain: "sistem-perizinan-santri-ec4de.firebaseapp.com",
    projectId: "sistem-perizinan-santri-ec4de",
    storageBucket: "sistem-perizinan-santri-ec4de.firebasestorage.app",
    messagingSenderId: "1065097278114",
    appId: "1:1065097278114:web:c22400c714f0d805ffddfb"
};

// VAPID KEY (Required for Web Push)
// TODO: User harus mengisi ini dari Firebase Console -> Project Settings -> Cloud Messaging -> Web Configuration
const VAPID_KEY = "BNEIgcr7NvMEGIiGFWZBZjfyPpd5lDcV8tgcJVnvw5iY2Kboaa06JwSqFPz15RMAZuWaO56Rka8Icd3yqnWhvPI";

// =================================================================
// LOGIKA PUSH NOTIFICATION (HYBRID: NATIVE + WEB)
// =================================================================
const initPushNotifications = async () => {
    const isNative = 'Capacitor' in window;

    // ----------------------------------------------------------------
    // JALUR 1: NATIVE (ANDROID APK)
    // ----------------------------------------------------------------
    if (isNative) {
        console.log('FCM: Initializing Native Mode...');
        try {
            // 1. Bersihkan listener lama
            await PushNotifications.removeAllListeners();

            // [FIX] Buat Channel Wajib untuk Android 8+
            // Channel ID diganti 'jadwal_mengajar_v2' agar settingan suara TER-UPDATE di HP user
            await PushNotifications.createChannel({
                id: 'jadwal_mengajar_v2',
                name: 'Jadwal Mengajar',
                description: 'Notifikasi jadwal pelajaran dengan suara khusus',
                importance: 5,
                visibility: 1,
                vibration: true,
                sound: 'notif_schedule.wav' // Custom Sound di res/raw/
            });

            // 2. Listener Token
            PushNotifications.addListener('registration', async (token) => {
                console.log('FCM Native Token:', token.value);
                await sendTokenToBackend(token.value);
            });

            // 3. Listener Error
            PushNotifications.addListener('registrationError', (error) => {
                console.error('Push Registration Error:', error);
            });

            // 4. Listener Notifikasi Masuk (Foreground)
            PushNotifications.addListener('pushNotificationReceived', async (notification) => {
                console.log('Push received:', notification);
                await LocalNotifications.schedule({
                    notifications: [{
                        title: notification.title || 'Info',
                        body: notification.body || '',
                        id: new Date().getTime(),
                        schedule: { at: new Date(new Date().getTime() + 100) },
                        sound: 'default',
                    }]
                });
            });

            // 5. Listener Notifikasi Diklik
            PushNotifications.addListener('pushNotificationActionPerformed', (notification) => {
                const notifId = notification.notification.data.notification_id;
                if (notifId) window.location.href = `/notifications/${notifId}`;
                else window.location.href = '/notifications';
            });

            // 6. Request Permission
            let permStatus = await PushNotifications.checkPermissions();
            if (permStatus.receive === 'prompt') {
                permStatus = await PushNotifications.requestPermissions();
            }
            if (permStatus.receive === 'granted') {
                await PushNotifications.register();
            }
        } catch (error) {
            console.error('FCM Native Setup Error:', error);
        }
    }

    // ----------------------------------------------------------------
    // JALUR 2: WEB (iOS Safari PWA / Chrome Desktop)
    // ----------------------------------------------------------------
    else {
        console.log('FCM: Initializing Web Mode...');
        try {
            // Cek apakah browser support SW & Push
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                console.log('Push Messaging not supported');
                return;
            }

            const app = initializeApp(firebaseConfig);
            const messaging = getMessaging(app);

            // Request Permission (Browser style)
            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                console.log('Notification permission granted.');

                // Get Token
                if (VAPID_KEY === "YOUR_VAPID_KEY_HERE") {
                    console.warn("VAPID KEY Belum diisi! Web Push tidak akan jalan.");
                    return;
                }

                const token = await getToken(messaging, {
                    vapidKey: VAPID_KEY
                });

                if (token) {
                    console.log('FCM Web Token:', token);
                    await sendTokenToBackend(token);
                } else {
                    console.log('No registration token available. Request permission to generate one.');
                }
            } else {
                console.log('Unable to get permission to notify.');
            }

            // Handle Foreground Message
            onMessage(messaging, (payload) => {
                console.log('Message received. ', payload);
                const { title, body } = payload.notification;
                // Tampilkan Toast / Custom UI
                new Notification(title, { body: body, icon: '/logo.png' });
            });

        } catch (error) {
            console.error('FCM Web Setup Error:', error);
        }
    }
};

// Helper: Kirim Token ke Laravel
const sendTokenToBackend = async (tokenValue) => {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
        if (csrfToken) headers['X-CSRF-TOKEN'] = csrfToken;

        await fetch('/notifications/fcm-token', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ token: tokenValue })
        });
        console.log('Token sent to backend');
    } catch (err) {
        console.error('Failed sending token to backend:', err);
    }
};


// =================================================================
// LOGIKA CEK UPDATE APLIKASI (PROFESSIONAL UI)
// =================================================================
const checkForUpdates = async () => {
    // [MODIFIED] Cek apakah sudah pernah cek update di sesi ini?
    // Agar popup tidak muncul terus setiap ganti halaman.
    if (sessionStorage.getItem('has_checked_update')) return;

    // Tandai sudah cek
    sessionStorage.setItem('has_checked_update', 'true');

    if (!('Capacitor' in window)) return;

    try {
        const info = await App.getInfo();
        const currentVersion = info.version;

        const response = await fetch('/api/check-update');
        const data = await response.json();
        const latestVersion = data.latest_version;

        if (isUpdateAvailable(currentVersion, latestVersion)) {
            // Tampilkan Modal Update
            showUpdateModal(latestVersion, data.download_url, data.release_notes);
        }

    } catch (e) {
        console.error('Update Check Failed:', e);
    }
};

// Helper: Show Beautiful Modal
function showUpdateModal(version, url, notes) {
    // 1. Cek jika modal sudah ada (biar gak dobel)
    if (document.getElementById('update-modal')) return;

    // 2. Buat Elemen Modal
    const modalHtml = `
        <div id="update-modal" class="fixed inset-0 z-[9999] flex items-center justify-center px-4 animate-fade-in">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeUpdateModal()"></div>
            
            <!-- Card -->
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all scale-100 p-6 text-center">
                <!-- Icon -->
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-red-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l3 3m0 0l3-3m-3 3v-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-2">Update Tersedia! 🚀</h3>
                <p class="text-gray-500 text-sm mb-6">
                    Versi terbaru <strong>v${version}</strong> sudah rilis. <br>
                    Yuk update biar aplikasi makin lancar jaya!
                </p>

                <div class="space-y-3">
                    <button onclick="window.location.href='${url}'" class="w-full inline-flex justify-center items-center rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-lg hover:bg-red-500 transition-all active:scale-95">
                        Update Sekarang
                    </button>
                    <button onclick="closeUpdateModal()" class="w-full inline-flex justify-center rounded-xl bg-slate-100 px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-200 transition-all">
                        Nanti Saja
                    </button>
                </div>
            </div>
        </div>
    `;

    // 3. Inject ke Body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

// Helper: Close Modal
window.closeUpdateModal = () => {
    const modal = document.getElementById('update-modal');
    if (modal) modal.remove();
};

// Helper: Compare version strings
function isUpdateAvailable(current, latest) {
    if (!current || !latest) return false;
    if (current === latest) return false;

    const v1 = current.split('.').map(Number);
    const v2 = latest.split('.').map(Number);

    for (let i = 0; i < Math.max(v1.length, v2.length); i++) {
        const num1 = v1[i] || 0;
        const num2 = v2[i] || 0;
        if (num2 > num1) return true;
        if (num2 < num1) return false;
    }
    return false;
}

document.addEventListener('DOMContentLoaded', () => {
    initPushNotifications();
    setTimeout(checkForUpdates, 2000);
});