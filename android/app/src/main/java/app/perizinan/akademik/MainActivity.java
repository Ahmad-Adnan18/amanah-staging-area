package app.perizinan.akademik;

import android.os.Bundle;
import com.getcapacitor.BridgeActivity;

// Import Wajib
import android.webkit.WebView;
import android.webkit.DownloadListener;
import android.webkit.CookieManager; // <--- INI KUNCINYA
import android.net.Uri;
import android.content.Intent;
import android.app.DownloadManager;
import android.os.Environment;
import android.content.Context;
import android.widget.Toast;
import android.view.WindowManager;

public class MainActivity extends BridgeActivity {

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // 1. Agar Status Bar Transparan/Menyatu (Sesuai diskusi sebelumnya)
        getWindow().clearFlags(WindowManager.LayoutParams.FLAG_LAYOUT_NO_LIMITS);

        // 2. Setting Download Listener dengan COOKIE
        this.getBridge().getWebView().setDownloadListener(new DownloadListener() {
            @Override
            public void onDownloadStart(String url, String userAgent,
                                        String contentDisposition, String mimetype,
                                        long contentLength) {
                try {
                    DownloadManager.Request request = new DownloadManager.Request(Uri.parse(url));

                    // --- BAGIAN SAKTI: AMBIL COOKIE DARI WEBVIEW ---
                    // Ini mengambil sesi login user yang sedang aktif
                    String cookie = CookieManager.getInstance().getCookie(url);
                    // Lalu tempelkan ke request download
                    request.addRequestHeader("Cookie", cookie);
                    request.addRequestHeader("User-Agent", userAgent);
                    // ------------------------------------------------

                    // Izinkan download lewat data seluler/wifi
                    request.setAllowedNetworkTypes(DownloadManager.Request.NETWORK_WIFI | DownloadManager.Request.NETWORK_MOBILE);

                    // Coba tebak nama file dari URL atau Content-Disposition
                    String fileName = url.substring(url.lastIndexOf("/") + 1);
                    // Jika ada parameter aneh di URL, bersihkan (opsional)
                    if (fileName.contains("?")) {
                        fileName = fileName.substring(0, fileName.indexOf("?"));
                    }

                    request.setTitle("Mendownload File...");
                    request.setDescription("Sedang mengunduh " + fileName);
                    request.allowScanningByMediaScanner();
                    request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED);

                    // Simpan ke folder Download publik
                    request.setDestinationInExternalPublicDir(Environment.DIRECTORY_DOWNLOADS, fileName);

                    DownloadManager dm = (DownloadManager) getSystemService(Context.DOWNLOAD_SERVICE);
                    dm.enqueue(request);

                    Toast.makeText(getApplicationContext(), "Mulai mengunduh...", Toast.LENGTH_SHORT).show();

                } catch (Exception e) {
                    // Fallback jika gagal: Buka di Chrome
                    try {
                        Intent intent = new Intent(Intent.ACTION_VIEW);
                        intent.setData(Uri.parse(url));
                        startActivity(intent);
                    } catch (Exception ex) {
                        Toast.makeText(getApplicationContext(), "Gagal mendownload", Toast.LENGTH_SHORT).show();
                    }
                }
            }
        });
    }
}
