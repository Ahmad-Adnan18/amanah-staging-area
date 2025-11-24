<?php

namespace App\Http\Controllers;

class DownloadController extends Controller
{
    public function show()
    {
        return response()->view('download');
    }

    public function apk()
    {
        $apkPath = public_path('downloads/Amanah.apk');

        if (! file_exists($apkPath)) {
            abort(404, 'Berkas APK tidak ditemukan.');
        }

        return response()->download($apkPath, 'Amanah.apk', [
            'Content-Type' => 'application/vnd.android.package-archive',
        ]);
    }
}
