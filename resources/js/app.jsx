import './bootstrap';
import Alpine from 'alpinejs';
import { createRoot } from 'react-dom/client';
import Dock from './components/Dock';

window.Alpine = Alpine;
Alpine.start();

let dockRoot = null;

const initMobileDock = () => {
    const container = document.getElementById('mobile-dock-root');
    if (!container) {
        return;
    }

    const rawItems = container.dataset.items ?? '[]';
    let parsedItems = [];

    try {
        parsedItems = JSON.parse(rawItems);
    } catch (error) {
        console.error('Gagal parsing data Dock:', error);
        return;
    }

    if (!Array.isArray(parsedItems) || parsedItems.length === 0) {
        return;
    }

    if (dockRoot) {
        dockRoot.unmount();
    }

    dockRoot = createRoot(container);
    dockRoot.render(<Dock items={parsedItems} />);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMobileDock);
} else {
    initMobileDock();
}

if (import.meta.hot) {
    import.meta.hot.dispose(() => {
        dockRoot?.unmount();
        dockRoot = null;
    });
}

// =================================================================
// KODE PWA UNTUK PROMOSI INSTALASI DAN SERVICE WORKER
// =================================================================

document.addEventListener('DOMContentLoaded', () => {
    let deferredPrompt = null;
    const installButton = document.getElementById('install-pwa-button'); // Pastikan ID ini sesuai dengan elemen di Blade

    // Fungsi helper untuk menampilkan/menyembunyikan tombol
    function toggleInstallButton(show) {
        if (installButton) {
            if (show) {
                installButton.style.display = 'block';
                installButton.classList.remove('hidden'); // Jika menggunakan Tailwind
            } else {
                installButton.style.display = 'none';
                installButton.classList.add('hidden'); // Jika menggunakan Tailwind
            }
        }
    }

    // 1. Registrasi service worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('Service Worker terdaftar dengan sukses:', registration);
            })
            .catch(error => {
                console.error('Gagal mendaftarkan Service Worker:', error);
            });
    } else {
        console.warn('Service Worker tidak didukung di browser ini.');
    }

    // 2. Tangkap event 'beforeinstallprompt'
    window.addEventListener('beforeinstallprompt', (e) => {
        console.log('Event beforeinstallprompt terdeteksi');
        e.preventDefault(); // Cegah browser menampilkan prompt default
        deferredPrompt = e;

        // Hanya tampilkan tombol instal kustom jika PWA belum diinstal
        // dan tombol instal ada di DOM
        if (installButton && !window.matchMedia('(display-mode: standalone)').matches) {
            toggleInstallButton(true);
            console.log('PWA siap diinstal. Tombol instal ditampilkan.');
        } else {
            toggleInstallButton(false); // Sembunyikan jika sudah terinstal atau tidak ada tombol
            console.log('PWA sudah terinstal atau tombol tidak ditemukan, tidak menampilkan tombol instal.');
        }
    });

    // 3. Pasang Event Listener 'click' LANGSUNG ke tombol instal PWA
    // Ini adalah bagian kunci untuk mengatasi NotAllowedError
    if (installButton) { // Pastikan tombol ada sebelum memasang event listener
        installButton.addEventListener('click', async () => {
            console.log('Tombol instal diklik');

            // Pastikan deferredPrompt ada
            if (deferredPrompt) {
                // Sembunyikan tombol segera setelah user mengklik
                toggleInstallButton(false);

                try {
                    // Panggil prompt() - INI ADALAH BARIS KRITIS YANG HARUS DALAM USER GESTURE
                    const choiceResult = await deferredPrompt.prompt();
                    console.log('Hasil pemilihan pengguna:', choiceResult.outcome);

                    // Reset deferredPrompt setelah digunakan
                    deferredPrompt = null;

                    if (choiceResult.outcome === 'accepted') {
                        console.log('User menginstal PWA.');
                    } else {
                        console.log('User membatalkan instalasi PWA.');
                        // Jika user membatalkan, bisa jadi ingin tombol muncul lagi nanti
                        // toggleInstallButton(true); // Opsional: tampilkan lagi jika dibatalkan
                    }
                } catch (error) {
                    console.error('Error saat menampilkan prompt instalasi:', error);
                    if (error.name === 'NotAllowedError') {
                        console.warn('Prompt instalasi diblokir. Pastikan klik tombol dilakukan langsung oleh user.');
                        toggleInstallButton(true); // Tampilkan lagi jika error agar user bisa coba lagi
                    }
                }
            } else {
                console.log('PWA belum siap diinstal atau sudah diinstal (deferredPrompt null).');
            }
        });
    }


    // 4. Tangani event 'appinstalled'
    window.addEventListener('appinstalled', () => {
        console.log('PWA berhasil diinstal!');
        toggleInstallButton(false); // Sembunyikan tombol setelah instalasi sukses
        deferredPrompt = null;
    });

    // 5. Cek mode standalone saat DOMContentLoaded (jika PWA sudah terinstal dan dibuka)
    // Ini untuk kasus refresh atau saat PWA dibuka dari icon home screen
    if (window.matchMedia('(display-mode: standalone)').matches) {
        console.log('PWA sudah berjalan dalam mode standalone (terinstal).');
        toggleInstallButton(false); // Pastikan tombol tidak terlihat jika sudah terinstal
    }
});