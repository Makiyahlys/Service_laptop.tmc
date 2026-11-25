@extends('layouts.app')

@section('title', 'Kasir Pembayaran & Verifikasi - Toko TMC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6 pb-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider block">FITUR 6 &bull; KASIR KEUANGAN</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Verifikasi Pembayaran & Kuitansi Digital
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Konfirmasi transaksi lunas (Tunai, QRIS, atau Transfer Bank) dan cetak nota kuitansi bergaransi resmi.
            </p>
        </div>
    </div>

    <!-- Antrean Unit Siap Bayar -->
    <div class="mb-10">
        <h2 class="text-base font-extrabold text-slate-900 mb-3 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
            <span>Antrean Unit Siap Diambil / Menunggu Pembayaran ({{ $siapBayar->count() }})</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($siapBayar as $item)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black text-sky-600">{{ $item->service_code }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                {{ $item->getStatusLabel() }}
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-slate-900">{{ $item->laptop_brand }} {{ $item->laptop_model }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pelanggan: <strong class="text-slate-800">{{ $item->customer_name }}</strong></p>

                        <div class="my-3 p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Biaya Sparepart:</span>
                                <span class="font-semibold">{{ $item->formatRupiah($item->cost_sparepart) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Biaya Jasa:</span>
                                <span class="font-semibold">{{ $item->formatRupiah($item->cost_service) }}</span>
                            </div>
                            <div class="pt-1.5 border-t border-slate-200 flex justify-between font-extrabold text-slate-900 text-sm">
                                <span>Tagihan:</span>
                                <span class="text-sky-700">{{ $item->formatRupiah($item->total_cost) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Verifikasi Pembayaran Cepat -->
                    <form action="{{ route('admin.pembayaran.proses', $item->service_code) }}" method="POST" class="pt-3 border-t border-slate-100 space-y-3">
                        @csrf
                        
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <label class="block font-bold text-slate-600 mb-1">Metode Bayar:</label>
                                <select name="payment_method" required class="w-full p-2 rounded-lg border border-slate-300 bg-white font-semibold">
                                    <option value="tunai">💵 Tunai / Cash</option>
                                    <option value="qris">📱 QRIS Statis/Dinamis</option>
                                    <option value="transfer">🏦 Transfer Bank</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-600 mb-1">Masa Garansi:</label>
                                <select name="warranty_period" required class="w-full p-2 rounded-lg border border-slate-300 bg-white font-semibold">
                                    <option value="30 Hari" selected>30 Hari</option>
                                    <option value="60 Hari">60 Hari</option>
                                    <option value="90 Hari">90 Hari</option>
                                    <option value="14 Hari">14 Hari</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <input 
                                type="hidden" 
                                name="paid_amount" 
                                value="{{ $item->total_cost }}"
                            >
                            <input 
                                type="text" 
                                name="payment_reference" 
                                placeholder="No. Referensi / Ref QRIS (Opsional)" 
                                class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs"
                            >
                        </div>

                        <button 
                            type="submit" 
                            onclick="return confirm('Konfirmasi pembayaran sebesar {{ $item->formatRupiah($item->total_cost) }} untuk {{ $item->service_code }}?')"
                            class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition active:scale-95 flex items-center justify-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Konfirmasi LUNAS & Terbitkan Kuitansi</span>
                        </button>

                    </form>

                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-8 text-center text-slate-400 italic">
                    Tidak ada unit yang sedang menunggu pembayaran kasir.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Riwayat Pembayaran Terakhir & Cetak Kuitansi -->
    <div>
        <h2 class="text-base font-extrabold text-slate-900 mb-3 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            <span>Riwayat Transaksi Lunas Terakhir</span>
        </h2>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-bold">
                        <tr>
                            <th class="py-3 px-4">Kode Servis</th>
                            <th class="py-3 px-4">Pelanggan</th>
                            <th class="py-3 px-4">Perangkat</th>
                            <th class="py-3 px-4">Metode & Ref</th>
                            <th class="py-3 px-4">Total Bayar</th>
                            <th class="py-3 px-4">Waktu Lunas</th>
                            <th class="py-3 px-4 text-center">Kuitansi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($riwayatLunas as $lunas)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-4 font-bold text-sky-600">{{ $lunas->service_code }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $lunas->customer_name }}</td>
                                <td class="py-3 px-4">{{ $lunas->laptop_brand }} {{ $lunas->laptop_model }}</td>
                                <td class="py-3 px-4 uppercase text-xs font-semibold">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">{{ $lunas->payment_method }}</span>
                                    @if($lunas->payment_reference)
                                        <span class="text-[10px] text-slate-400 block mt-0.5">{{ $lunas->payment_reference }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-extrabold text-emerald-700">{{ $lunas->formatRupiah($lunas->paid_amount) }}</td>
                                <td class="py-3 px-4 text-xs text-slate-500">{{ $lunas->paid_at ? $lunas->paid_at->format('d/m/Y H:i') : '-' }}</td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('admin.kuitansi', $lunas->service_code) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-sky-50 text-sky-700 border border-sky-200 hover:bg-sky-100 font-bold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>Kuitansi PDF</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-slate-400 italic">Belum ada riwayat transaksi lunas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
