import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography"; // Import typography plugin
import aspectRatio from "@tailwindcss/aspect-ratio"; // Import aspect-ratio plugin
import containerQueries from "@tailwindcss/container-queries"; // Import container-queries plugin
import lineClamp from "@tailwindcss/line-clamp"; // Import line-clamp plugin

/** @type {import('tailwindcss').Config} */
export default {
    // Jalur ke semua file yang berisi kelas Tailwind CSS.
    // Ini penting agar Tailwind bisa melakukan 'purging' (menghapus kelas yang tidak terpakai).
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js", // Tambahkan ini jika kamu punya file JS (Vue, Alpine, React)
        "./resources/js/**/*.jsx", // Pastikan file React JSX juga discan
        "./resources/js/**/*.ts",
        "./resources/js/**/*.tsx",
        "./resources/js/**/*.vue", // Tambahkan ini jika kamu pakai Vue.js
        "./resources/css/**/*.css", // Jika kamu punya CSS custom yang berisi kelas Tailwind
    ],

    theme: {
        extend: {
            // Menambahkan breakpoint kustom jika diperlukan (contoh: 'xs' untuk ekstra kecil)
            screens: {
                xs: "475px", // Contoh breakpoint tambahan untuk ponsel yang sangat kecil
                ...defaultTheme.screens,
            },
            fontFamily: {
                // 'Figtree' adalah default Laravel baru-baru ini, sangat bagus.
                // Pastikan untuk meng-import defaultTheme di awal file.
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                // Contoh menambahkan font kustom lain (misal: 'Inter', pastikan di-import di CSS utama)
                // heading: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Menambahkan palet warna kustom sesuai branding atau kebutuhan aplikasi
                // Contoh: Warna primer (misal: merah) dan sekunder (misal: abu-abu)
                primary: defaultTheme.colors.red, // Menggunakan palet merah Tailwind sebagai primary
                secondary: defaultTheme.colors.slate, // Menggunakan palet slate Tailwind sebagai secondary
                // Atau bisa juga custom warna heksadesimal:
                // primary: {
                //     50: '#FDF2F2',
                //     100: '#FDECEC',
                //     200: '#FBC5C5',
                //     300: '#F89B9B',
                //     400: '#F46565',
                //     500: '#EF4444', // Merah kustom
                //     600: '#DC2626',
                //     700: '#B91C1C',
                //     800: '#991B1B',
                //     900: '#7F1D1D',
                //     950: '#450A0A',
                // },
                // customGray: {
                //     100: '#f7f7f7',
                //     200: '#e5e5e5',
                //     // ... dan seterusnya
                // }
            },
        },
    },

    // Menambahkan plugin-plugin yang telah di-import
    plugins: [
        forms, // Plugin untuk menata elemen form secara default
        typography, // Plugin untuk menata konten tipografi (misal: dari markdown)
        aspectRatio, // Plugin untuk menjaga rasio aspek (misal: pada gambar atau video)
        containerQueries, // Plugin untuk query container (komponen responsif terhadap ukurannya sendiri)
        lineClamp, // Plugin untuk membatasi jumlah baris teks dan menambahkan elipsis
    ],
};
