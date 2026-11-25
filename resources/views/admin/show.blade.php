@extends('layouts.app')

@section('title', 'Detail Servis ' . $ticket->service_code . ' - Toko TMC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-200 gap-3 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">DETAIL TIKET SERVIS</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold uppercase {{ $ticket->getStatusBadge() }}">
                    {{ $ticket->getStatusLabel() }}
                </span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 mt-1">{{ $ticket->service_code }}</h1>
            <span class="text-xs text-slate-500">Terdaftar pada: {{ $ticket->created_at->format('d F Y, H:i') }} WIB oleh {{ $ticket->created_by }}</span>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.tanda-terima', $ticket->service_code) }}" target="_blank" class="px-3.5 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Tanda Terima</span>
            </a>

            <a href="{{ route('teknisi.edit', $ticket->service_code) }}" class="px-3.5 py-2 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold flex items-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Buka Work Order Teknisi</span>
            </a>

            @if($ticket->payment_status === 'lunas')
                <a href="{{ route('admin.kuitansi', $ticket->service_code) }}" target="_blank" class="px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-1.5 shadow-sm">
                    <span>Lihat Kuitansi</span>
                </a>
            @endif
        </div>
    </div>

    <!-- WhatsApp Quick Send Bar (Fitur 5) -->
    <div class="bg-gradient-to-r from-emerald-900 to-teal-900 text-white rounded-2xl p-4 sm:p-5 shadow-sm mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.07L2 22l5.07-1.34C8.5 21.52 10.2 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-300">FITUR 5: WhatsApp Bot Notifikasi</span>
            </div>
            <p class="text-xs text-slate-200 mt-1">Kirim pesan otomatis langsung ke nomor WhatsApp pelanggan: <strong>{{ $ticket->customer_phone }} ({{ $ticket->customer_name }})</strong></p>
        </div>

        <div class="flex flex-wrap items-center gap-1.5">
            <a href="{{ $ticket->getWhatsAppUrl('intake') }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-sm border border-white/20">
                Penerimaan
            </a>
            <a href="{{ $ticket->getWhatsAppUrl('estimation') }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-sm border border-white/20">
                Estimasi & Approval
            </a>
            <a href="{{ $ticket->getWhatsAppUrl('ready') }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-sm border border-white/20">
                Siap Diambil
            </a>
            <a href="{{ $ticket->getWhatsAppUrl('paid') }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-sm border border-white/20">
                Kuitansi Lunas
            </a>
        </div>
    </div>

    <!-- 2 Columns Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Left: Detail Pelanggan & Laptop -->
        <div class="md:col-span-2 space-y-6">
            
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100 mb-4">
                    Data Pelanggan & Unit Laptop
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">Nama Pelanggan:</span>
                        <span class="font-bold text-slate-800">{{ $ticket->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">No. WhatsApp / Telepon:</span>
                        <a href="https://wa.me/{{ $ticket->getCleanPhone() }}" target="_blank" class="font-bold text-emerald-600 hover:underline">
                            {{ $ticket->customer_phone }} &nearr;
                        </a>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">Merk & Tipe:</span>
                        <span class="font-bold text-slate-800">{{ $ticket->laptop_brand }} {{ $ticket->laptop_model }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">Nomor Seri (S/N):</span>
                        <span class="font-medium text-slate-700">{{ $ticket->serial_number ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">Kelengkapan Unit:</span>
                        <span class="font-medium text-slate-700">{{ $ticket->completeness ?: 'Unit saja' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold">Password / PIN:</span>
                        <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">{{ $ticket->laptop_password ?: 'Tidak ada' }}</span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-slate-400 block text-xs font-semibold">Kondisi Fisik Awal:</span>
                        <span class="text-slate-700">{{ $ticket->physical_condition ?: '-' }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-slate-400 block text-xs font-semibold mb-1">Keluhan Awal:</span>
                    <p class="text-sm font-medium text-slate-800 bg-slate-50 p-3 rounded-xl border border-slate-200">
                        {{ $ticket->initial_complaint }}
                    </p>
                </div>
            </div>

            <!-- Diagnosa & Tindakan Teknisi -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">
                        Hasil Diagnosa & Work Order Teknisi
                    </h2>
                    <span class="text-xs text-slate-500 font-medium">Teknisi: <strong>{{ $ticket->technician_name ?: 'Belum ditentukan' }}</strong></span>
                </div>

                <div class="space-y-3 text-xs sm:text-sm">
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold mb-1">Diagnosa Kerusakan:</span>
                        <p class="text-slate-800 bg-slate-50 p-3 rounded-xl border border-slate-200">
                            {{ $ticket->diagnosis ?: 'Belum diisi oleh teknisi.' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-xs font-semibold mb-1">Rincian Kebutuhan Sparepart:</span>
                        <p class="text-slate-800 bg-slate-50 p-3 rounded-xl border border-slate-200">
                            {{ $ticket->spareparts_detail ?: 'Tidak ada sparepart.' }}
                        </p>
                    </div>

                    @if($ticket->approval_notes)
                        <div>
                            <span class="text-slate-400 block text-xs font-semibold mb-1">Catatan Persetujuan Pelanggan:</span>
                            <p class="text-amber-900 bg-amber-50 p-2.5 rounded-lg border border-amber-200 text-xs italic">
                                "{{ $ticket->approval_notes }}" ({{ $ticket->approval_at ? $ticket->approval_at->format('d/m/Y H:i') : '' }})
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right: Status Keuangan & Timeline -->
        <div class="space-y-6">
            
            <!-- Keuangan Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100 mb-4">
                    Biaya & Pembayaran
                </h2>

                <div class="space-y-2.5 text-xs sm:text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Sparepart</span>
                        <span class="font-bold text-slate-800">{{ $ticket->formatRupiah($ticket->cost_sparepart) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Jasa Servis</span>
                        <span class="font-bold text-slate-800">{{ $ticket->formatRupiah($ticket->cost_service) }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-200 flex justify-between text-base font-extrabold text-slate-900">
                        <span>Total Biaya</span>
                        <span class="text-sky-700">{{ $ticket->formatRupiah($ticket->total_cost) }}</span>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-medium">Status Bayar:</span>
                        <span class="text-xs font-bold uppercase px-2.5 py-1 rounded {{ $ticket->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $ticket->payment_status === 'lunas' ? 'LUNAS' : 'BELUM BAYAR' }}
                        </span>
                    </div>

                    @if($ticket->payment_status === 'lunas')
                        <div class="pt-2 text-xs text-slate-500 space-y-1">
                            <p>Metode: <strong>{{ strtoupper($ticket->payment_method) }}</strong></p>
                            <p>Ref: <strong>{{ $ticket->payment_reference ?: '-' }}</strong></p>
                            <p>Waktu: {{ $ticket->paid_at ? $ticket->paid_at->format('d/m/Y H:i') : '-' }}</p>
                            <p>Garansi: {{ $ticket->warranty_period }} (s/d {{ $ticket->warranty_until ? $ticket->warranty_until->format('d/m/Y') : '-' }})</p>
                        </div>
                    @else
                        <div class="pt-3">
                            <a href="{{ route('admin.pembayaran') }}" class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow transition">
                                <span>Proses Pembayaran Kasir &rarr;</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Riwayat Log -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100 mb-4">
                    Riwayat Aktivitas Log
                </h2>

                <div class="space-y-3">
                    @forelse($ticket->logs as $log)
                        <div class="text-xs pb-2.5 border-b border-slate-100 last:border-b-0">
                            <div class="flex items-center justify-between text-[11px] text-slate-400 mb-0.5">
                                <span class="font-bold text-slate-700">{{ $log->actor }}</span>
                                <span>{{ $log->created_at->format('d/m H:i') }}</span>
                            </div>
                            <span class="font-bold text-slate-800 block">{{ $log->title }}</span>
                            @if($log->note)
                                <p class="text-slate-500 mt-0.5">{{ $log->note }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Belum ada catatan log.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
