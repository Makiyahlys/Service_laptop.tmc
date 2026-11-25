@extends('layouts.app')

@section('title', 'Live Tracking Progres Servis Laptop - Toko TMC')

@section('content')
<div class="py-10 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    
    <!-- Hero Header -->
    <div class="text-center max-w-2xl mx-auto mb-8">
        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-300 mb-3">
            FITUR 2: Real-Time Live Tracking
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Pantau Progres Servisan Laptop Anda
        </h1>
        <p class="text-sm text-slate-600 mt-2">
            Masukkan Kode Servis yang tertera pada nota penerimaan Anda untuk melihat tahapan perbaikan secara transparan.
        </p>

        <!-- Form Pencarian -->
        <form action="{{ route('tracking.index') }}" method="GET" class="mt-6 flex flex-col sm:flex-row gap-2 max-w-lg mx-auto">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="kode" 
                    value="{{ request('kode', $ticket?->service_code) }}" 
                    placeholder="Contoh: TMC-8821 atau TMC-202611-0002"
                    required
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-800 font-semibold placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 shadow-sm"
                >
            </div>
            <button type="submit" class="px-6 py-3 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-sm transition active:scale-95 flex items-center justify-center gap-1.5">
                <span>Lacak Unit</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <!-- Tombol Demo Cepat -->
        <div class="mt-3 flex flex-wrap items-center justify-center gap-1.5 text-xs text-slate-500">
            <span>Coba kode demo:</span>
            <a href="{{ route('tracking.index', ['kode' => 'TMC-8821']) }}" class="px-2 py-0.5 rounded bg-white border border-slate-300 hover:border-sky-500 text-sky-700 font-semibold">TMC-8821 (Pengerjaan)</a>
            <a href="{{ route('tracking.index', ['kode' => 'TMC-202611-0002']) }}" class="px-2 py-0.5 rounded bg-white border border-slate-300 hover:border-sky-500 text-amber-700 font-semibold">TMC-202611-0002 (Approval)</a>
            <a href="{{ route('tracking.index', ['kode' => 'TMC-9902']) }}" class="px-2 py-0.5 rounded bg-white border border-slate-300 hover:border-sky-500 text-emerald-700 font-semibold">TMC-9902 (Siap Ambil)</a>
            <a href="{{ route('tracking.index', ['kode' => 'TMC-202611-0005']) }}" class="px-2 py-0.5 rounded bg-white border border-slate-300 hover:border-sky-500 text-emerald-700 font-semibold">TMC-202611-0005 (Lunas)</a>
        </div>
    </div>

    @if($searched && !$ticket)
        <!-- Not Found State -->
        <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center max-w-lg mx-auto shadow-sm">
            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Kode Servis Tidak Ditemukan</h3>
            <p class="text-xs text-slate-500 mt-1">Pastikan kode servis yang Anda masukkan sudah sesuai dengan yang ada pada nota atau pesan WhatsApp.</p>
        </div>
    @elseif($ticket)
        <!-- Result Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            
            <!-- Header Kartu -->
            <div class="p-6 bg-gradient-to-r from-slate-900 to-sky-950 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-black tracking-wider bg-sky-500 text-white">
                            {{ $ticket->service_code }}
                        </span>
                        <span class="text-xs text-sky-200">
                            Masuk: {{ $ticket->created_at->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-white mt-1.5">
                        {{ $ticket->laptop_brand }} {{ $ticket->laptop_model }}
                    </h2>
                    <p class="text-xs text-slate-300 mt-0.5">
                        Pelanggan: <strong class="text-white">{{ $ticket->customer_name }}</strong> &bull; No. Seri: {{ $ticket->serial_number ?: '-' }}
                    </p>
                </div>

                <div class="sm:text-right">
                    <span class="text-[11px] font-semibold text-sky-200 uppercase tracking-wider block">Status Terkini:</span>
                    <span class="inline-block mt-1 px-3.5 py-1.5 rounded-full text-xs font-black uppercase border {{ $ticket->getStatusBadge() }}">
                        {{ $ticket->getStatusLabel() }}
                    </span>
                </div>
            </div>

            <!-- Visual Stepper Progress Bar -->
            <div class="p-6 sm:p-8 border-b border-slate-200 bg-slate-50/50">
                <div class="max-w-3xl mx-auto">
                    @php $step = $ticket->getProgressStepIndex(); @endphp
                    
                    <div class="relative">
                        <!-- Progress line background -->
                        <div class="hidden sm:block absolute top-1/2 left-0 right-0 h-1 bg-slate-200 -translate-y-1/2 z-0"></div>
                        <div class="hidden sm:block absolute top-1/2 left-0 h-1 bg-sky-600 -translate-y-1/2 z-0 transition-all duration-500" style="width: {{ $step * 25 }}%;"></div>

                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 sm:gap-2 relative z-10">
                            
                            <!-- Step 0: Penerimaan -->
                            <div class="flex sm:flex-col items-center gap-3 sm:gap-2 sm:text-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shadow-sm {{ $step >= 0 ? 'bg-sky-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                    ✓
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Unit Diterima</div>
                                    <div class="text-[10px] text-slate-400">Pendaftaran Loket</div>
                                </div>
                            </div>

                            <!-- Step 1: Diagnosa -->
                            <div class="flex sm:flex-col items-center gap-3 sm:gap-2 sm:text-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shadow-sm {{ $step >= 1 ? 'bg-sky-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                    {{ $step > 1 ? '✓' : '1' }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Pengecekan</div>
                                    <div class="text-[10px] text-slate-400">Analisa Kerusakan</div>
                                </div>
                            </div>

                            <!-- Step 2: Persetujuan Biaya -->
                            <div class="flex sm:flex-col items-center gap-3 sm:gap-2 sm:text-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shadow-sm {{ $step >= 2 ? ($ticket->status === 'ditolak' ? 'bg-rose-600 text-white' : 'bg-sky-600 text-white') : 'bg-slate-200 text-slate-500' }}">
                                    {{ $step > 2 ? '✓' : '2' }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Konfirmasi Biaya</div>
                                    <div class="text-[10px] text-slate-400">Persetujuan Klien</div>
                                </div>
                            </div>

                            <!-- Step 3: Pengerjaan -->
                            <div class="flex sm:flex-col items-center gap-3 sm:gap-2 sm:text-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shadow-sm {{ $step >= 3 ? 'bg-sky-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                    {{ $step > 3 ? '✓' : '3' }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Pengerjaan</div>
                                    <div class="text-[10px] text-slate-400">Servis & Penggantian</div>
                                </div>
                            </div>

                            <!-- Step 4: Selesai -->
                            <div class="flex sm:flex-col items-center gap-3 sm:gap-2 sm:text-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shadow-sm {{ $step >= 4 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                    {{ $step >= 4 ? '✓' : '4' }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Siap Diambil</div>
                                    <div class="text-[10px] text-slate-400">Kuitansi & Garansi</div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- FITUR 4: Banner Interaktif Jika Status Membutuhkan Client Approval -->
            @if($ticket->status === 'menunggu_persetujuan')
                <div class="bg-amber-50 border-b border-amber-200 p-5 sm:p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-amber-900">Konfirmasi Persetujuan Biaya Diperlukan (Fitur 4)</h4>
                            <p class="text-xs text-amber-700 mt-0.5">Teknisi telah menyelesaikan diagnosa awal dengan estimasi biaya total <strong>{{ $ticket->formatRupiah($ticket->total_cost) }}</strong>.</p>
                        </div>
                    </div>
                    <a href="{{ route('tracking.approval', $ticket->service_code) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs sm:text-sm shadow-sm transition flex-shrink-0">
                        <span>Review & Tentukan Keputusan &rarr;</span>
                    </a>
                </div>
            @endif

            <!-- FITUR 6: Banner Jika Sudah Lunas (Bisa Cetak Kuitansi) -->
            @if($ticket->payment_status === 'lunas')
                <div class="bg-emerald-50 border-b border-emerald-200 p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="text-xs sm:text-sm font-bold text-emerald-900">
                            Pembayaran LUNAS via {{ strtoupper($ticket->payment_method) }}. Garansi berlaku s/d {{ $ticket->warranty_until ? $ticket->warranty_until->format('d/m/Y') : '-' }}.
                        </span>
                    </div>
                    <a href="{{ route('public.kuitansi', $ticket->service_code) }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Unduh Kuitansi Digital (PDF)</span>
                    </a>
                </div>
            @endif

            <!-- Rincian Informasi Kerusakan & Diagnosa -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-xs sm:text-sm">
                
                <!-- Kolom Kiri: Detail Kerusakan & Keluhan Awal -->
                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Keluhan Awal:</span>
                        <p class="font-medium text-slate-800 mt-1 bg-slate-50 p-3 rounded-lg border border-slate-200">
                            {{ $ticket->initial_complaint }}
                        </p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Kelengkapan Diterima:</span>
                        <p class="text-slate-700 mt-0.5">{{ $ticket->completeness ?: 'Unit saja' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Teknisi Penanggung Jawab:</span>
                        <p class="text-slate-800 font-semibold mt-0.5">{{ $ticket->technician_name ?: 'Menunggu Alokasi Teknisi' }}</p>
                    </div>
                </div>

                <!-- Kolom Kanan: Hasil Diagnosa & Biaya -->
                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Hasil Diagnosa Teknisi:</span>
                        <p class="font-medium text-slate-800 mt-1 bg-slate-50 p-3 rounded-lg border border-slate-200">
                            {{ $ticket->diagnosis ?: 'Laptop sedang dalam antrean pemeriksaan fisik dan komponen.' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Kebutuhan Sparepart:</span>
                        <p class="text-slate-700 mt-0.5">{{ $ticket->spareparts_detail ?: 'Belum ada rincian sparepart' }}</p>
                    </div>

                    <div class="pt-3 border-t border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-500 block">Total Estimasi / Biaya:</span>
                            <span class="text-lg font-black text-slate-900">{{ $ticket->formatRupiah($ticket->total_cost) }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block">Status Pembayaran:</span>
                            <span class="text-xs font-bold uppercase px-2.5 py-1 rounded {{ $ticket->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $ticket->payment_status === 'lunas' ? 'LUNAS' : 'BELUM BAYAR' }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Riwayat Aktivitas & Log Pengerjaan (Timeline) -->
            <div class="p-6 bg-slate-50 border-t border-slate-200">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Riwayat Progres Pengerjaan</h3>
                <div class="space-y-3">
                    @forelse($ticket->logs as $log)
                        <div class="flex items-start gap-3 text-xs">
                            <span class="w-2 h-2 rounded-full bg-sky-500 mt-1.5 flex-shrink-0"></span>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800">{{ $log->title }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-200 text-slate-600 font-semibold">{{ $log->actor }}</span>
                                </div>
                                @if($log->note)
                                    <p class="text-slate-600 mt-0.5">{{ $log->note }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Belum ada catatan log aktivitas.</p>
                    @endforelse
                </div>
            </div>

            <!-- Tombol Aksi Tambahan: Cetak Tanda Terima & Hubungi WA -->
            <div class="p-4 bg-white border-t border-slate-200 flex flex-wrap items-center justify-between gap-3 text-xs">
                <a href="{{ route('public.tanda-terima', $ticket->service_code) }}" target="_blank" class="inline-flex items-center gap-1.5 text-slate-600 hover:text-slate-900 font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Tanda Terima Loket</span>
                </a>

                <a href="https://wa.me/6281234567890?text=Halo%20Toko%20TMC%2C%20saya%20ingin%20menanyakan%20status%20servis%20laptop%20saya%20dengan%20kode%20{{ $ticket->service_code }}" target="_blank" class="inline-flex items-center gap-1.5 text-emerald-600 font-bold hover:underline">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.07L2 22l5.07-1.34C8.5 21.52 10.2 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
                    <span>Tanya Customer Service Toko TMC</span>
                </a>
            </div>

        </div>
    @endif

</div>
@endsection
