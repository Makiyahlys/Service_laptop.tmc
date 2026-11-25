@extends('layouts.app')

@section('title', 'Kasir & Penerimaan Servis - Toko TMC Laptop')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    
    <!-- Top Action & Title -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-200 gap-4 mb-6">
        <div>
            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider block">FITUR 1 & MODUL KASIR</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Pencatatan Penerimaan Unit Servis
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kelola pendaftaran unit laptop baru, pantau antrean loket, dan terbitkan tanda terima digital.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow transition active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Input Penerimaan Baru</span>
            </a>
        </div>
    </div>

    <!-- Quick Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6 text-xs sm:text-sm">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-slate-400 block text-[11px] font-semibold">Total Seluruh Unit</span>
            <span class="text-xl font-black text-slate-900">{{ $counts['total'] }}</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-slate-400 block text-[11px] font-semibold">Antrean Loket</span>
            <span class="text-xl font-black text-amber-600">{{ $counts['antrean'] }}</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-slate-400 block text-[11px] font-semibold">Dalam Pengerjaan</span>
            <span class="text-xl font-black text-sky-600">{{ $counts['pengerjaan'] }}</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-slate-400 block text-[11px] font-semibold">Siap Diambil</span>
            <span class="text-xl font-black text-indigo-600">{{ $counts['siap_diambil'] }}</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-slate-400 block text-[11px] font-semibold">Selesai & Lunas</span>
            <span class="text-xl font-black text-emerald-600">{{ $counts['selesai'] }}</span>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
        <form action="{{ route('admin.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            
            <!-- Pencarian Kata Kunci -->
            <div class="sm:col-span-5 relative">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}" 
                    placeholder="Cari Kode Servis, Nama Pelanggan, No WA, Laptop..."
                    class="w-full pl-3 pr-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                >
            </div>

            <!-- Filter Status -->
            <div class="sm:col-span-3">
                <select name="status" class="w-full py-2 px-3 rounded-lg border border-slate-300 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                    <option value="">-- Semua Status --</option>
                    <option value="antrean" {{ request('status') === 'antrean' ? 'selected' : '' }}>Antrean Masuk</option>
                    <option value="pengecekan" {{ request('status') === 'pengecekan' ? 'selected' : '' }}>Sedang Diagnosa</option>
                    <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="pengerjaan" {{ request('status') === 'pengerjaan' ? 'selected' : '' }}>Dalam Pengerjaan</option>
                    <option value="siap_diambil" {{ request('status') === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai / Lunas</option>
                </select>
            </div>

            <!-- Filter Pembayaran -->
            <div class="sm:col-span-2">
                <select name="payment_status" class="w-full py-2 px-3 rounded-lg border border-slate-300 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                    <option value="">-- Pembayaran --</option>
                    <option value="belum_bayar" {{ request('payment_status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="lunas" {{ request('payment_status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <!-- Tombol Filter -->
            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-2 px-4 rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs shadow transition">
                    Filter
                </button>
                @if(request()->anyFilled(['q', 'status', 'payment_status']))
                    <a href="{{ route('admin.index') }}" class="py-2 px-3 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100 text-xs font-semibold flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Table List Servis -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-bold">
                    <tr>
                        <th class="py-3.5 px-4">Kode & Tanggal</th>
                        <th class="py-3.5 px-4">Pelanggan</th>
                        <th class="py-3.5 px-4">Perangkat Laptop</th>
                        <th class="py-3.5 px-4">Keluhan Awal</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Biaya</th>
                        <th class="py-3.5 px-4 text-center">Aksi & WA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-slate-50/80 transition">
                            
                            <!-- Kode & Tanggal -->
                            <td class="py-3.5 px-4">
                                <a href="{{ route('admin.show', $ticket->service_code) }}" class="font-extrabold text-sky-600 hover:underline block text-xs sm:text-sm">
                                    {{ $ticket->service_code }}
                                </a>
                                <span class="text-[11px] text-slate-400">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                            </td>

                            <!-- Pelanggan -->
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $ticket->customer_name }}</span>
                                <span class="text-xs text-slate-500">{{ $ticket->customer_phone }}</span>
                            </td>

                            <!-- Perangkat -->
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800 block">{{ $ticket->laptop_brand }} {{ $ticket->laptop_model }}</span>
                                <span class="text-[11px] text-slate-400">SN: {{ $ticket->serial_number ?: '-' }}</span>
                            </td>

                            <!-- Keluhan -->
                            <td class="py-3.5 px-4 max-w-xs">
                                <span class="line-clamp-2 text-xs text-slate-600">{{ $ticket->initial_complaint }}</span>
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold uppercase border {{ $ticket->getStatusBadge() }}">
                                    {{ $ticket->getStatusLabel() }}
                                </span>
                            </td>

                            <!-- Biaya & Bayar -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block">{{ $ticket->formatRupiah($ticket->total_cost) }}</span>
                                <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded {{ $ticket->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $ticket->payment_status === 'lunas' ? 'LUNAS' : 'BELUM BAYAR' }}
                                </span>
                            </td>

                            <!-- Aksi & WA -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Detail -->
                                    <a href="{{ route('admin.show', $ticket->service_code) }}" class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>

                                    <!-- Tanda Terima -->
                                    <a href="{{ route('admin.tanda-terima', $ticket->service_code) }}" target="_blank" class="p-1.5 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-700 transition" title="Cetak Tanda Terima Masuk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>

                                    <!-- WA Bot Direct Trigger -->
                                    <a href="{{ $ticket->getWhatsAppUrl('intake') }}" target="_blank" class="p-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition" title="Kirim Pesan WhatsApp">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.07L2 22l5.07-1.34C8.5 21.52 10.2 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
                                    </a>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 italic">
                                Belum ada data penerimaan unit servis yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
