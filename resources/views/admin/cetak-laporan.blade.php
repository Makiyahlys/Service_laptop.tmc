<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Servis - Toko TMC Laptop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-area { border: none !important; box-shadow: none !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 text-slate-800 antialiased text-xs sm:text-sm">

    <!-- Action Bar -->
    <div class="max-w-5xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="{{ route('admin.laporan') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-slate-900">
            &larr; Kembali ke Modul Laporan
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-lg shadow transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak Dokumen Laporan (Print / PDF)</span>
        </button>
    </div>

    <!-- Paper Sheet Container -->
    <div class="max-w-5xl mx-auto bg-white border border-slate-300 rounded-2xl p-8 sm:p-10 shadow-sm print-area">
        
        <!-- Header Laporan -->
        <div class="flex items-start justify-between pb-4 border-b-2 border-slate-900 gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">TOKO TMC LAPTOP</h1>
                <p class="text-xs text-slate-600 font-semibold">Laporan Rekapitulasi Servis & Rekonsiliasi Pendapatan Toko</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Jl. Kramat Raya No. 88, Senen, Jakarta Pusat &bull; Telp/WA: 0812-3456-7890</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider rounded">
                    DOKUMEN EKSEKUTIF
                </span>
                <div class="text-xs text-slate-600 mt-1.5 font-bold">
                    Periode: {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}
                </div>
                <div class="text-[10px] text-slate-400">Dicetak pada: {{ date('d/m/Y H:i') }} WIB</div>
            </div>
        </div>

        <!-- KPI Ringkasan Singkat -->
        <div class="my-6 grid grid-cols-4 gap-3 text-xs bg-slate-50 p-4 rounded-xl border border-slate-200">
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block">Total Unit Diterima:</span>
                <span class="text-base font-black text-slate-900">{{ $records->count() }} Unit</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block">Unit Selesai & Lunas:</span>
                <span class="text-base font-black text-emerald-700">{{ $totalLunas }} Transaksi</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block">Total Omset Pendapatan:</span>
                <span class="text-base font-black text-sky-800">Rp {{ number_format($totalOmset, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block">Rata-rata Nilai Servis:</span>
                <span class="text-base font-black text-slate-800">
                    Rp {{ number_format($records->count() > 0 ? ($totalOmset / max(1, $totalLunas)) : 0, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Tabel Rincian -->
        <table class="w-full text-left text-xs mb-8">
            <thead>
                <tr class="border-b-2 border-slate-800 text-slate-700 font-bold uppercase text-[10px]">
                    <th class="py-2 px-1">No</th>
                    <th class="py-2 px-2">Kode Servis</th>
                    <th class="py-2 px-2">Pelanggan</th>
                    <th class="py-2 px-2">Perangkat</th>
                    <th class="py-2 px-2">Keluhan / Diagnosa</th>
                    <th class="py-2 px-2 text-right">Sparepart</th>
                    <th class="py-2 px-2 text-right">Jasa</th>
                    <th class="py-2 px-2 text-right">Total</th>
                    <th class="py-2 px-2 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-slate-800">
                @forelse($records as $idx => $r)
                    <tr>
                        <td class="py-2 px-1 font-mono text-[11px]">{{ $idx + 1 }}</td>
                        <td class="py-2 px-2 font-bold">{{ $r->service_code }}</td>
                        <td class="py-2 px-2">{{ $r->customer_name }}</td>
                        <td class="py-2 px-2">{{ $r->laptop_brand }} {{ $r->laptop_model }}</td>
                        <td class="py-2 px-2 max-w-xs text-[11px]">{{ Str::limit($r->diagnosis ?: $r->initial_complaint, 45) }}</td>
                        <td class="py-2 px-2 text-right">{{ number_format($r->cost_sparepart, 0, ',', '.') }}</td>
                        <td class="py-2 px-2 text-right">{{ number_format($r->cost_service, 0, ',', '.') }}</td>
                        <td class="py-2 px-2 text-right font-bold">{{ number_format($r->total_cost, 0, ',', '.') }}</td>
                        <td class="py-2 px-2 text-center">
                            <span class="text-[10px] uppercase font-bold {{ $r->payment_status === 'lunas' ? 'text-emerald-700' : 'text-slate-500' }}">
                                {{ $r->payment_status === 'lunas' ? 'LUNAS' : 'PENDING' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-6 text-center text-slate-400 italic">Tidak ada data untuk dicetak.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-slate-900 font-bold text-slate-900 text-xs">
                    <td colspan="7" class="py-3 text-right">TOTAL PENDAPATAN TERVERIFIKASI (LUNAS):</td>
                    <td colspan="2" class="py-3 text-right text-sm font-black text-emerald-800">
                        Rp {{ number_format($totalOmset, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 text-center text-xs pt-6 border-t border-slate-300">
            <div>
                <span class="text-slate-500 block mb-14">Dibuat oleh (Admin Kasir),</span>
                <strong class="text-slate-800 block underline">( Kasir Toko TMC )</strong>
            </div>
            <div>
                <span class="text-slate-500 block mb-14">Mengetahui & Menyetujui (Owner / Pimpinan),</span>
                <strong class="text-slate-800 block underline">( Owner Toko TMC Laptop )</strong>
            </div>
        </div>

    </div>

</body>
</html>
