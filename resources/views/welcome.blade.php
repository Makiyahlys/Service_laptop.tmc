<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMC Laptop - Layanan Servis & Cek Status Perbaikan</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                            800: '#0c4a6e',
                            900: '#082f49',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- ==================== NAVBAR SEDERHANA ==================== -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            
            <!-- Logo Sederhana -->
            <a href="/" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-sky-600 flex items-center justify-center text-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-bold text-slate-900 tracking-tight">TMC <span class="text-sky-600">Laptop</span></span>
                    <span class="block text-[11px] text-slate-500 font-medium -mt-1">Service & Maintenance</span>
                </div>
            </a>

            <!-- Menu Link & Tombol WhatsApp -->
            <div class="flex items-center gap-6">
                <div class="hidden md:flex items-center gap-5 text-sm font-medium text-slate-600">
                    <a href="#tracking" class="hover:text-sky-600 transition">Cek Status</a>
                    <a href="#layanan" class="hover:text-sky-600 transition">Layanan</a>
                    <a href="#kontak" class="hover:text-sky-600 transition">Kontak</a>
                </div>

                <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs sm:text-sm shadow-sm transition">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.044c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.825zM12 2C6.477 2 2 6.477 2 12c0 1.891.524 3.66 1.434 5.176L2 22l4.954-1.396A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/>
                    </svg>
                    <span>Konsultasi WA</span>
                </a>
            </div>

        </div>
    </nav>

    <!-- ==================== HERO SECTION LEBIH RINGKAS & BERSIH ==================== -->
    <header class="py-14 sm:py-20 px-4 sm:px-6 bg-gradient-to-b from-white to-slate-100/60 border-b border-slate-200">
        <div class="max-w-3xl mx-auto text-center" id="tracking">
            
            <!-- Badge Singkat -->
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/60 mb-4">
                Pelayanan Cepat & Bergaransi
            </span>

            <!-- Judul Bersih -->
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight mb-3">
                Layanan Perbaikan & Servis Laptop
            </h1>

            <p class="text-sm sm:text-base text-slate-600 max-w-xl mx-auto mb-8 leading-relaxed">
                Pantau status pengerjaan laptop Anda secara mudah dan real-time. Masukkan nomor nota / kode servis Anda di bawah ini:
            </p>

            <!-- Card Pencarian Sederhana -->
            <div class="max-w-xl mx-auto bg-white p-2 rounded-xl shadow-sm border border-slate-300 flex flex-col sm:flex-row gap-2">
                <div class="flex-1 flex items-center px-3 py-1.5">
                    <svg class="w-5 h-5 text-slate-400 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input 
                        type="text" 
                        id="kodeServis"
                        placeholder="Masukkan Kode Servis (misal: TMC-01)"
                        value="TMC-01"
                        class="w-full text-slate-800 placeholder-slate-400 text-sm focus:outline-none bg-transparent"
                    />
                </div>
                <button 
                    onclick="cekStatusServis()"
                    class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-semibold text-sm rounded-lg transition active:scale-95 shadow-sm"
                >
                    Cek Status
                </button>
            </div>

            <p class="text-xs text-slate-400 mt-3">
                Tips: Coba gunakan kode demo <button onclick="isiDanCek('TMC-01')" class="text-sky-600 underline font-medium">TMC-01</button> atau <button onclick="isiDanCek('TMC-02')" class="text-sky-600 underline font-medium">TMC-02</button>
            </p>

            <!-- ==================== HASIL TRACKING SEDERHANA ==================== -->
            <div id="hasilTracking" class="mt-8 text-left bg-white rounded-xl border border-slate-200 p-5 sm:p-6 shadow-sm hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded bg-sky-100 text-sky-800" id="hasilKode">TMC-01</span>
                            <span class="text-xs text-slate-400">Masuk: 02 Nov 2026</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mt-1" id="hasilLaptop">Asus ROG Zephyrus G14</h3>
                    </div>
                    <div>
                        <span class="text-xs px-3 py-1 rounded-full font-semibold bg-amber-100 text-amber-800 inline-block" id="hasilStatus">
                            Sedang Dikerjakan Teknisi
                        </span>
                    </div>
                </div>

                <div class="py-4 text-xs sm:text-sm text-slate-600 space-y-1.5">
                    <p><strong>Keluhan:</strong> <span id="hasilKeluhan">Mati total / Overheating</span></p>
                    <p><strong>Rincian Pengerjaan:</strong> Pembersihan heatsink, penggantian thermal paste liquid metal, pengecekan jalur IC power.</p>
                    <p><strong>Estimasi Selesai:</strong> Hari ini, pukul 17:00 WIB</p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Butuh info lebih lanjut?</span>
                    <a href="https://wa.me/6281234567890" target="_blank" class="text-emerald-600 font-semibold hover:underline">
                        Hubungi Teknisi via WA &rarr;
                    </a>
                </div>
            </div>

        </div>
    </header>

    <!-- ==================== DAFTAR LAYANAN RINGKAS ==================== -->
    <main class="py-12 px-4 sm:px-6 max-w-5xl mx-auto" id="layanan">
        <div class="text-center mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Layanan yang Tersedia</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kami menangani berbagai kendala laptop dengan teknisi berpengalaman.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            
            <!-- Layanan 1 -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/90 shadow-sm hover:border-sky-300 transition">
                <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold mb-3.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-base mb-1">Perbaikan Motherboard</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Mengatasi laptop mati total, konslet, no display, perbaikan IC power, dan reballing chipset.
                </p>
            </div>

            <!-- Layanan 2 -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/90 shadow-sm hover:border-sky-300 transition">
                <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold mb-3.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-base mb-1">Ganti Sparepart</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Penggantian layar LCD pecah/garis, baterai drop, keyboard rusak, engsel patah, dan charger adaptor.
                </p>
            </div>

            <!-- Layanan 3 -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/90 shadow-sm hover:border-sky-300 transition">
                <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold mb-3.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-base mb-1">Upgrade & Maintenance</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Cleaning debu kipas, ganti thermal paste agar tidak cepat panas, upgrade RAM dan SSD cepat.
                </p>
            </div>

        </div>
    </main>

    <!-- ==================== FOOTER RINGKAS ==================== -->
    <footer class="bg-white border-t border-slate-200 py-6 text-xs text-slate-500" id="kontak">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div>
                <span class="font-bold text-slate-800">TMC Laptop</span> — Layanan Perbaikan Laptop Terpercaya
            </div>
            <div>
                Alamat: Toko TMC Laptop &bull; WhatsApp: <a href="https://wa.me/6281234567890" target="_blank" class="text-sky-600 hover:underline">0812-3456-7890</a>
            </div>
            <div class="text-slate-400">
                &copy; {{ date('Y') }} TMC Laptop. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Script Sederhana -->
    <script>
        function isiDanCek(kode) {
            document.getElementById('kodeServis').value = kode;
            cekStatusServis();
        }

        function cekStatusServis() {
            const input = document.getElementById('kodeServis').value.trim();
            const hasil = document.getElementById('hasilTracking');

            if (!input) {
                alert('Silakan ketik kode servis Anda terlebih dahulu.');
                return;
            }

            hasil.classList.remove('hidden');

            if (input.toUpperCase() === 'TMC-02') {
                document.getElementById('hasilKode').innerText = 'TMC-02';
                document.getElementById('hasilLaptop').innerText = 'Lenovo Ideapad Slim 3';
                document.getElementById('hasilKeluhan').innerText = 'Upgrade SSD NVMe 512GB & Ganti Baterai';
                document.getElementById('hasilStatus').innerText = 'Selesai - Siap Diambil';
                document.getElementById('hasilStatus').className = 'text-xs px-3 py-1 rounded-full font-semibold bg-emerald-100 text-emerald-800 inline-block';
            } else {
                document.getElementById('hasilKode').innerText = input.toUpperCase();
                document.getElementById('hasilLaptop').innerText = 'Asus ROG Zephyrus G14';
                document.getElementById('hasilKeluhan').innerText = 'Mati total / Overheating';
                document.getElementById('hasilStatus').innerText = 'Sedang Dikerjakan Teknisi';
                document.getElementById('hasilStatus').className = 'text-xs px-3 py-1 rounded-full font-semibold bg-amber-100 text-amber-800 inline-block';
            }
        }
    </script>
</body>
</html>
