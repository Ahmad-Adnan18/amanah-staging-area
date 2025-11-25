import './bootstrap';
import Alpine from 'alpinejs';
import { createRoot } from 'react-dom/client';
import GlassIcons from './components/GlassIcons';
import { App } from '@capacitor/app';
// --- 1. TAMBAHAN IMPORT BIOMETRIC & PREFERENCES ---
import { NativeBiometric } from '@capgo/capacitor-native-biometric';
import { Preferences } from '@capacitor/preferences';

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