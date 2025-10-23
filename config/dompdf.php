<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DomPDF Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for DomPDF, used to generate PDF files.
    | These settings control how DomPDF handles fonts, file access, and rendering.
    |
    */

    'enable_remote' => true, // Izinkan akses file eksternal (misalnya, URL dari Storage::url())
    'chroot' => [
        public_path(), // Izinkan akses ke folder public
        storage_path(), // Izinkan akses ke folder storage
    ],
    'font_dir' => storage_path('app/public/fonts/'), // Folder untuk menyimpan font
    'font_cache' => storage_path('app/public/fonts/'), // Cache untuk font
    'default_font' => 'Times New Roman', // Font default untuk PDF
    'is_html5_parser_enabled' => true, // Aktifkan parser HTML5
    'is_remote_enabled' => true, // Alias untuk enable_remote (untuk kompatibilitas)
    'is_font_subsetting_enabled' => false, // Nonaktifkan subsetting font untuk ukuran file lebih kecil
    'log_output_file' => storage_path('logs/dompdf.log'), // File log untuk debugging
    'temp_dir' => storage_path('app/public/temp/'), // Folder sementara untuk DomPDF
];