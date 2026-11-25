@extends('layouts.app')

@section('title', 'Konfirmasi Persetujuan Biaya Servis - ' . $ticket->service_code)

@section('content')
<div class="py-10 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
    
    <!-- Header -->
    <div class="text-center mb-8">
        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 mb-2">
            FITUR 4: Client Approval Online
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Konfirmasi Persetujuan Estimasi Biaya
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 mt-1">
            Silakan tinjau rincian diagnosa teknisi dan tentukan keputusan Anda sebelum teknisi kami memulai perbaikan.
        </p>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        
        <!-- Header Info -->
        <div class="p-6 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="text-xs font-bold text-sky-400 block">{{ $ticket->service_code }}</span>
                <h2 class="text-xl font-bold">{{ $ticket->laptop_brand }} {{ $ticket->laptop_model }}</h2>
                <span class="text-xs text-slate-400">Pemilik: {{ $ticket->customer_name }}</span>
            </div>
            <div>
                <span class="text-xs px-3 py-1.5 rounded-full font-bold uppercase tracking-wider {{ $ticket->getStatusBadge() }}">
                    {{ $ticket->getStatusLabel() }}
                </span>
            </div>
        </div>

        <!-- Body Rincian Diagnosa -->
        <div class="p-6 sm:p-8 space-y-6">
            
            <!-- Keluhan Awal -->
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Keluhan Saat Masuk:</span>
                <p class="text-sm font-medium text-slate-800 mt-1 bg-slate-50 p-3 rounded-lg border border-slate-200">
                    {{ $ticket->initial_complaint }}
                </p>
            </div>

            <!-- Diagnosa Teknisi -->
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Hasil Diagnosa Teknisi ({{ $ticket->technician_name ?: 'Teknisi TMC' }}):</span>
                <div class="text-sm text-slate-800 mt-1 bg-sky-50/60 p-4 rounded-xl border border-sky-200 leading-relaxed">
                    {{ $ticket->diagnosis ?: 'Belum ada rincian diagnosa tertulis.' }}
                </div>
            </div>

            <!-- Rincian Biaya & Sparepart -->
            <div class="bg-slate-50 rounded-xl p-5 border border-slate-200">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Rincian Estimasi Biaya Perbaikan</h3>
                
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between pb-2 border-b border-slate-200">
                        <span class="text-slate-600">Rincian Sparepart: <strong class="text-slate-800 block text-xs mt-0.5">{{ $ticket->spareparts_detail ?: 'Tidak ada penggantian sparepart' }}</strong></span>
                        <span class="font-semibold text-slate-800">{{ $ticket->formatRupiah($ticket->cost_sparepart) }}</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b border-slate-200">
                        <span class="text-slate-600">Biaya Jasa Servis / Reparasi</span>
                        <span class="font-semibold text-slate-800">{{ $ticket->formatRupiah($ticket->cost_service) }}</span>
                    </div>

                    <div class="flex justify-between pt-2 text-base font-extrabold text-slate-900">
                        <span>Total Estimasi Biaya</span>
                        <span class="text-sky-700 text-lg">{{ $ticket->formatRupiah($ticket->total_cost) }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-200 text-xs text-slate-500 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Garansi pengerjaan: <strong>{{ $ticket->warranty_period }}</strong> setelah unit selesai.</span>
                </div>
            </div>

            <!-- Jika Sudah Mengambil Keputusan -->
            @if($ticket->approval_status !== 'pending' && $ticket->status !== 'menunggu_persetujuan')
                <div class="p-4 rounded-xl border text-sm {{ $ticket->approval_status === 'approved' ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : 'bg-rose-50 border-rose-300 text-rose-900' }}">
                    <div class="flex items-center gap-2 font-bold">
                        <span>Status Konfirmasi: {{ $ticket->approval_status === 'approved' ? 'DISETUJUI OLEH PELANGGAN' : 'DITOLAK OLEH PELANGGAN' }}</span>
                    </div>
                    @if($ticket->approval_at)
                        <span class="text-xs text-slate-500 block mt-1">Dikonfirmasi pada: {{ $ticket->approval_at->format('d/m/Y H:i') }} WIB</span>
                    @endif
                    @if($ticket->approval_notes)
                        <p class="text-xs mt-2 italic">"{{ $ticket->approval_notes }}"</p>
                    @endif
                </div>

                <div class="text-center pt-2">
                    <a href="{{ route('tracking.index', ['kode' => $ticket->service_code]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900 transition">
                        <span>&larr; Kembali ke Live Tracking</span>
                    </a>
                </div>
            @else
                <!-- Form Persetujuan Interaktif -->
                <form action="{{ route('tracking.approval.submit', $ticket->service_code) }}" method="POST" class="pt-4 border-t border-slate-200">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Catatan atau Pesan Tambahan untuk Teknisi (Opsional):
                        </label>
                        <textarea 
                            name="notes" 
                            rows="2" 
                            placeholder="Contoh: Mohon pastikan data di harddisk tetap aman ya mas..." 
                            class="w-full p-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white"
                        ></textarea>
                    </div>

                    <!-- 2 Tombol Aksi: Setuju / Tolak -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        
                        <!-- Tombol Setuju -->
                        <button 
                            type="submit" 
                            name="decision" 
                            value="setuju"
                            onclick="return confirm('Apakah Anda yakin menyetujui estimasi biaya sebesar {{ $ticket->formatRupiah($ticket->total_cost) }}?')"
                            class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow transition flex items-center justify-center gap-2 active:scale-95"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Setuju & Lanjutkan Servis</span>
                        </button>

                        <!-- Tombol Tolak -->
                        <button 
                            type="submit" 
                            name="decision" 
                            value="tolak"
                            onclick="return confirm('Apakah Anda yakin membatalkan perbaikan ini? Unit akan dipersiapkan untuk diambil kembali.')"
                            class="w-full py-3.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm shadow transition flex items-center justify-center gap-2 active:scale-95"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Tolak & Batalkan Servis</span>
                        </button>

                    </div>

                </form>
            @endif

        </div>

    </div>

</div>
@endsection
