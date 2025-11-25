package app.perizinan.akademik; // Pastikan package ini sesuai dengan punyamu

import android.os.Bundle;
import com.getcapacitor.BridgeActivity;

// Import Standar
import android.webkit.WebView;
import android.webkit.DownloadListener;
import android.webkit.CookieManager;
import android.net.Uri;
import android.content.Intent;
import android.app.DownloadManager;
import android.os.Environment;
import android.content.Context;
import android.widget.Toast;
import android.view.WindowManager;

// Import Tambahan untuk Offline Handler & Cache
import android.webkit.WebViewClient;
import android.webkit.WebResourceRequest;
import android.webkit.WebResourceError;

public class MainActivity extends BridgeActivity {

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // 1. Agar Status Bar Transparan/Menyatu & Layout Rapi
        getWindow().clearFlags(WindowManager.LayoutParams.FLAG_LAYOUT_NO_LIMITS);

        // Cek null safety sebelum akses WebView
        if (this.getBridge() != null && this.getBridge().getWebView() != null) {
            WebView webView = this.getBridge().getWebView();

            // -----------------------------------------------------------
            // FITUR 2: OFFLINE HANDLER (Agar tidak stuck layar abu-abu)
            // -----------------------------------------------------------
            webView.setWebViewClient(new WebViewClient() {
                @Override
                public void onReceivedError(WebView view, WebResourceRequest request, WebResourceError error) {
                    // Jika URL utama gagal dimuat (misal DNS error/Internet mati)
                    if (request.isForMainFrame()) {
                        // Alihkan ke file offline.html lokal
                        // Pastikan kamu sudah buat file offline.html di folder public!
                        view.loadUrl("file:///android_asset/public/offline.html");
                    }
                }
            });

            // -----------------------------------------------------------
            // FITUR 3: DOWNLOAD MANAGER DENGAN COOKIE (Kode Kamu)
            // -----------------------------------------------------------
            webView.setDownloadListener(new DownloadListener() {
                @Override
                public void onDownloadStart(String url, String userAgent,
                                            String contentDisposition, String mimetype,
                                            long contentLength) {
                    try {
                        DownloadManager.Request request = new DownloadManager.Request(Uri.parse(url));

                        // AMBIL COOKIE (Agar bisa download file yang butuh login)
                        String cookie = CookieManager.getInstance().getCookie(url);
                        request.addRequestHeader("Cookie", cookie);
                        request.addRequestHeader("User-Agent", userAgent);

                        // Izinkan download lewat data seluler/wifi
                        request.setAllowedNetworkTypes(DownloadManager.Request.NETWORK_WIFI | DownloadManager.Request.NETWORK_MOBILE);

                        // Tebak nama file
                        String fileName = url.substring(url.lastIndexOf("/") + 1);
                        if (fileName.contains("?")) {
                            fileName = fileName.substring(0, fileName.indexOf("?"));
                        }

                        request.setTitle("Mendownload File...");
                        request.setDescription("Sedang mengunduh " + fileName);
                        request.allowScanningByMediaScanner();
                        request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED);

                        request.setDestinationInExternalPublicDir(Environment.DIRECTORY_DOWNLOADS, fileName);

                        DownloadManager dm = (DownloadManager) getSystemService(Context.DOWNLOAD_SERVICE);
                        dm.enqueue(request);

                        Toast.makeText(getApplicationContext(), "Mulai mengunduh...", Toast.LENGTH_SHORT).show();

                    } catch (Exception e) {
                        // Fallback ke Browser Chrome jika gagal
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

    // -----------------------------------------------------------
    // FITUR 4: AUTO CLEAN CACHE (Agar Aplikasi Tidak Bengkak)
    // -----------------------------------------------------------
    @Override
    public void onDestroy() {
        super.onDestroy();
        // Bersihkan cache saat aplikasi ditutup total dari Recent Apps
        if (this.getBridge() != null && this.getBridge().getWebView() != null) {
            this.getBridge().getWebView().clearCache(true);
            this.getBridge().getWebView().clearHistory();
        }
    }
}
