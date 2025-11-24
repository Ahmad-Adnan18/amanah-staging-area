package app.perizinan.akademik;

import android.os.Bundle;
import com.getcapacitor.BridgeActivity;

// --- TAMBAHAN IMPORT UNTUK DOWNLOAD ---
import android.webkit.WebView;
import android.webkit.DownloadListener;
import android.net.Uri;
import android.content.Intent;
import android.app.DownloadManager;
import android.os.Environment;
import android.content.Context;
import android.widget.Toast;
// --------------------------------------

public class MainActivity extends BridgeActivity {

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Kode agar WebView bisa menangani Download
        this.getBridge().getWebView().setDownloadListener(new DownloadListener() {
            @Override
            public void onDownloadStart(String url, String userAgent,
                                        String contentDisposition, String mimetype,
                                        long contentLength) {
                try {
                    DownloadManager.Request request = new DownloadManager.Request(Uri.parse(url));

                    // Izinkan download lewat data seluler atau wifi
                    request.setAllowedNetworkTypes(DownloadManager.Request.NETWORK_WIFI | DownloadManager.Request.NETWORK_MOBILE);
                    request.setTitle("Download File"); // Judul di notifikasi
                    request.setDescription("Sedang mengunduh file...");
                    request.allowScanningByMediaScanner();

                    // Tampilkan notifikasi saat download berjalan & selesai
                    request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED);

                    // Simpan ke folder Download publik di HP
                    request.setDestinationInExternalPublicDir(Environment.DIRECTORY_DOWNLOADS, url.substring(url.lastIndexOf("/") + 1));

                    DownloadManager dm = (DownloadManager) getSystemService(Context.DOWNLOAD_SERVICE);
                    dm.enqueue(request);

                    Toast.makeText(getApplicationContext(), "Sedang mengunduh...", Toast.LENGTH_SHORT).show();
                } catch (Exception e) {
                    // Fallback: Jika gagal, buka link di browser eksternal (Chrome)
                    Intent intent = new Intent(Intent.ACTION_VIEW);
                    intent.setData(Uri.parse(url));
                    startActivity(intent);
                }
            }
        });
    }
}
