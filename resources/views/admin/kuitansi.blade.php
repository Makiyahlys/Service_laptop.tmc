<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi Pembayaran - {{ $ticket->service_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-code { font-family: 'Space Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .kuitansi-box { border: none !important; box-shadow: none !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 text-slate-800 antialiased text-xs sm:text-sm">

    <!-- Top Action Bar -->
    <div class="max-w-3xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="{{ route('admin.pembayaran') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-slate-900">
            &larr; Kembali ke Kasir
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ $ticket->getWhatsAppUrl('paid') }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow transition flex items-center gap-1.5">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.07L2 22l5.07-1.34C8.5 21.52 10.2 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
                <span>Kirim Link Kuitansi via WhatsApp</span>
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg shadow transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Nota (Print / PDF)</span>
            </button>
        </div>
    </div>

    <!-- Paper Sheet Container -->
    <div class="max-w-3xl mx-auto bg-white border border-slate-300 rounded-2xl p-8 sm:p-10 shadow-sm kuitansi-box relative overflow-hidden">
        
        <!-- Stamp Watermark Lunas -->
        <div class="absolute right-12 top-24 pointer-events-none opacity-20 transform -rotate-12 border-4 border-emerald-600 rounded-xl px-6 py-2 text-3xl font-black text-emerald-700 tracking-widest uppercase">
            LUNAS - PAID
        </div>

        <!-- Header Nota -->
        <div class="flex items-start justify-between pb-6 border-b-2 border-slate-900 gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">TOKO TMC LAPTOP</h1>
                <p class="text-xs text-slate-600 font-medium">Layanan Perbaikan Laptop Profesional & Spesialis Hardware</p>
                <p class="text-[11px] text-slate-500 mt-0.5">
                    Jl. Kramat Raya No. 88, Senen, Jakarta Pusat &bull; WhatsApp: 0812-3456-7890
                </p>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-emerald-600 text-white font-black text-xs uppercase tracking-wider rounded">
                    KUITANSI RESMI
                </span>
                <div class="font-mono-code font-bold text-sm text-slate-900 mt-1">{{ $ticket->service_code }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5">
                    Tanggal: {{ $ticket->paid_at ? $ticket->paid_at->format('d F Y, H:i') : date('d F Y') }} WIB
                </div>
            </div>
        </div>

        <!-- Data Pelanggan & Perangkat -->
        <div class="py-5 grid grid-cols-2 gap-6 text-xs border-b border-slate-200">
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Ditagihkan Kepada:</span>
                <strong class="text-slate-900 text-sm block">{{ $ticket->customer_name }}</strong>
                <span class="text-slate-600 block">{{ $ticket->customer_phone }}</span>
                <span class="text-slate-500 block">{{ $ticket->customer_address ?: '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Perangkat Servis:</span>
                <strong class="text-slate-900 text-sm block">{{ $ticket->laptop_brand }} {{ $ticket->laptop_model }}</strong>
                <span class="text-slate-600 block">Nomor Seri: {{ $ticket->serial_number ?: '-' }}</span>
                <span class="text-slate-600 block">Teknisi: {{ $ticket->technician_name ?: 'Teknisi TMC' }}</span>
            </div>
        </div>

        <!-- Tabel Rincian Biaya -->
        <div class="py-6">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-300 text-slate-500 font-bold uppercase text-[10px]">
                        <th class="py-2.5">Deskripsi Pekerjaan / Komponen</th>
                        <th class="py-2.5 text-center">Qty</th>
                        <th class="py-2.5 text-right">Biaya (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-800">
                    <!-- Jasa -->
                    <tr>
                        <td class="py-3">
                            <span class="font-bold block">Biaya Jasa Servis & Pengecekan</span>
                            <span class="text-[11px] text-slate-500">{{ $ticket->diagnosis ?: 'Perbaikan perangkat dan pengujian sistem.' }}</span>
                        </td>
                        <td class="py-3 text-center">1</td>
                        <td class="py-3 text-right font-semibold">{{ $ticket->formatRupiah($ticket->cost_service) }}</td>
                    </tr>

                    <!-- Sparepart -->
                    @if($ticket->cost_sparepart > 0)
                        <tr>
                            <td class="py-3">
                                <span class="font-bold block">Penggantian Sparepart</span>
                                <span class="text-[11px] text-slate-500">{{ $ticket->spareparts_detail }}</span>
                            </td>
                            <td class="py-3 text-center">1</td>
                            <td class="py-3 text-right font-semibold">{{ $ticket->formatRupiah($ticket->cost_sparepart) }}</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-800 font-bold text-slate-900 text-sm">
                        <td colspan="2" class="py-3 text-right">TOTAL PEMBAYARAN:</td>
                        <td class="py-3 text-right text-base text-emerald-700 font-black">{{ $ticket->formatRupiah($ticket->paid_amount ?: $ticket->total_cost) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Info & Guarantee Box -->
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 grid grid-cols-2 gap-4 text-xs mb-8">
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block">Metode Pembayaran:</span>
                <span class="font-extrabold text-slate-800 text-sm block mt-0.5 uppercase">{{ $ticket->payment_method ?: 'TUNAI' }}</span>
                <span class="text-slate-500 text-[11px]">Ref: {{ $ticket->payment_reference ?: 'Trx-Kasir-Valid' }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block">Jaminan Garansi Servis:</span>
                <span class="font-extrabold text-emerald-700 text-sm block mt-0.5">{{ $ticket->warranty_period }}</span>
                <span class="text-slate-500 text-[11px]">Berlaku sampai: <strong>{{ $ticket->warranty_until ? $ticket->warranty_until->format('d F Y') : '-' }}</strong></span>
            </div>
        </div>

        <!-- Ketentuan Garansi -->
        <div class="text-[10px] text-slate-500 space-y-1 mb-8">
            <p>1. Garansi berlaku untuk kerusakan dan komponen yang sama sesuai rincian pada kuitansi ini.</p>
            <p>2. Segel garansi toko pada unit laptop tidak boleh rusak, sobek, atau dibuka oleh pihak lain.</p>
            <p>3. Garansi tidak berlaku untuk kerusakan akibat kelalaian pengguna (terjatuh, terkena cairan, atau lonjakan arus petir).</p>
        </div>

        <!-- Signatures & QR -->
        <div class="grid grid-cols-3 gap-4 text-center text-xs pt-4 border-t border-slate-300 items-end">
            <div>
                <span class="text-slate-400 block text-[10px] mb-12">Penerima Unit / Pelanggan,</span>
                <strong class="text-slate-800 block text-xs">({{ $ticket->customer_name }})</strong>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-14 h-14 bg-slate-100 border border-slate-300 rounded flex items-center justify-center text-[9px] text-slate-600 font-mono text-center mb-1">
                    QRIS / Valid
                </div>
                <span class="text-[9px] text-slate-400">Verifikasi Digital</span>
            </div>

            <div>
                <span class="text-slate-400 block text-[10px] mb-12">Kasir Toko TMC Laptop,</span>
                <strong class="text-slate-800 block text-xs">({{ $ticket->created_by }})</strong>
            </div>
        </div>

    </div>

</body>
</html>
