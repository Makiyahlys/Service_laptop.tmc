<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima Servis - {{ $ticket->service_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-code { font-family: 'Space Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-card { border: none !important; box-shadow: none !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 text-slate-800 antialiased text-xs sm:text-sm">

    <!-- Action Bar -->
    <div class="max-w-2xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="{{ route('admin.show', $ticket->service_code) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-slate-900">
            &larr; Kembali ke Detail Tiket
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg shadow transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak Tanda Terima (Print / PDF)</span>
        </button>
    </div>

    <!-- Paper Card Container -->
    <div class="max-w-2xl mx-auto bg-white border border-slate-300 rounded-2xl p-6 sm:p-8 shadow-sm print-card">
        
        <!-- Header Nota -->
        <div class="flex items-start justify-between pb-4 border-b-2 border-slate-800 gap-4">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">TOKO TMC LAPTOP</h1>
                <p class="text-xs text-slate-600">Layanan Perbaikan Laptop Profesional & Spesialis Motherboard</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Jl. Kramat Raya No. 88, Jakarta Pusat &bull; WhatsApp: 0812-3456-7890</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider rounded">
                    TANDA TERIMA
                </span>
                <div class="font-mono-code font-bold text-sm text-slate-900 mt-1.5">{{ $ticket->service_code }}</div>
                <div class="text-[10px] text-slate-400">{{ $ticket->created_at->format('d/m/Y H:i') }} WIB</div>
            </div>
        </div>

        <!-- Info Pelanggan & Perangkat -->
        <div class="py-4 grid grid-cols-2 gap-4 text-xs border-b border-slate-200">
            <div>
                <span class="text-slate-400 font-bold block uppercase text-[10px]">Data Pelanggan:</span>
                <span class="font-bold text-slate-800 block text-sm mt-0.5">{{ $ticket->customer_name }}</span>
                <span class="text-slate-600 block">{{ $ticket->customer_phone }}</span>
                <span class="text-slate-500 block">{{ $ticket->customer_address ?: '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold block uppercase text-[10px]">Data Perangkat:</span>
                <span class="font-bold text-slate-800 block text-sm mt-0.5">{{ $ticket->laptop_brand }} {{ $ticket->laptop_model }}</span>
                <span class="text-slate-600 block">S/N: {{ $ticket->serial_number ?: '-' }}</span>
                <span class="text-slate-600 block">Kelengkapan: {{ $ticket->completeness ?: 'Unit saja' }}</span>
            </div>
        </div>

        <!-- Keluhan Awal & Fisik -->
        <div class="py-4 space-y-3 text-xs border-b border-slate-200">
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-0.5">Keluhan Awal:</span>
                <p class="font-medium text-slate-800 bg-slate-50 p-2.5 rounded border border-slate-200">
                    {{ $ticket->initial_complaint }}
                </p>
            </div>

            <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-600">
                <div>Kondisi Fisik: <strong class="text-slate-800">{{ $ticket->physical_condition ?: '-' }}</strong></div>
                <div>Password Testing: <strong class="font-mono text-slate-800">{{ $ticket->laptop_password ?: 'Tidak ada' }}</strong></div>
            </div>
        </div>

        <!-- Live Tracking Notice -->
        <div class="py-4 bg-sky-50 rounded-xl p-3 my-4 border border-sky-200 text-xs text-sky-900 flex items-center justify-between">
            <div>
                <span class="font-bold block">Pantau Status Servis Secara Real-Time:</span>
                <span class="text-[11px] text-sky-700">Akses link: <strong>{{ url('/tracking?kode=' . $ticket->service_code) }}</strong></span>
            </div>
            <div class="w-12 h-12 bg-white border border-sky-300 rounded flex items-center justify-center font-bold text-[9px] text-sky-800 text-center uppercase">
                QR Code<br>Tracking
            </div>
        </div>

        <!-- Ketentuan Tanda Terima -->
        <div class="text-[10px] text-slate-500 space-y-1 mb-8">
            <p>1. Tanda terima ini adalah bukti sah penyerahan unit servis di Toko TMC Laptop.</p>
            <p>2. Pengambilan unit wajib menunjukkan tanda terima ini atau verifikasi nomor WhatsApp terdaftar.</p>
            <p>3. Estimasi biaya perbaikan dan kebutuhan sparepart akan dikonfirmasikan sebelum teknisi bekerja.</p>
            <p>4. Unit yang tidak diambil dalam waktu 60 hari setelah pemberitahuan selesai berada di luar tanggung jawab toko.</p>
        </div>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 text-center text-xs pt-4 border-t border-slate-300">
            <div>
                <span class="text-slate-500 block mb-12">Diserahkan oleh (Pelanggan),</span>
                <strong class="text-slate-800 block">({{ $ticket->customer_name }})</strong>
            </div>
            <div>
                <span class="text-slate-500 block mb-12">Diterima oleh (Kasir Toko TMC),</span>
                <strong class="text-slate-800 block">({{ $ticket->created_by }})</strong>
            </div>
        </div>

    </div>

</body>
</html>
