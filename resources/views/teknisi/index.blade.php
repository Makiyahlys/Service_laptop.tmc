@extends('layouts.app')

@section('title', 'Dashboard Work Order Teknisi - Toko TMC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-200 gap-4 mb-6">
        <div>
            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider block">FITUR 3 &bull; WORKBENCH TEKNISI</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Modul Work Order & Diagnosa Kerusakan
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Pilih unit servis dari antrean untuk menginput analisa diagnosa, kebutuhan sparepart, dan estimasi biaya perbaikan.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-300 font-bold text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Workbench Teknisi Aktif</span>
            </span>
        </div>
    </div>

    <!-- Filter Tab Bar -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3 mb-6 text-xs font-bold">
        <a href="{{ route('teknisi.index', ['tab' => 'aktif']) }}" class="px-3.5 py-2 rounded-lg transition {{ $status === 'aktif' ? 'bg-sky-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }}">
            Semua Aktif ({{ $counts['total_aktif'] }})
        </a>
        <a href="{{ route('teknisi.index', ['tab' => 'antrean']) }}" class="px-3.5 py-2 rounded-lg transition {{ $status === 'antrean' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }}">
            Menunggu Diagnosa ({{ $counts['antrean'] }})
        </a>
        <a href="{{ route('teknisi.index', ['tab' => 'approval']) }}" class="px-3.5 py-2 rounded-lg transition {{ $status === 'approval' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }}">
            Menunggu Persetujuan Biaya ({{ $counts['approval'] }})
        </a>
        <a href="{{ route('teknisi.index', ['tab' => 'pengerjaan']) }}" class="px-3.5 py-2 rounded-lg transition {{ $status === 'pengerjaan' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }}">
            Sedang Dikerjakan ({{ $counts['pengerjaan'] }})
        </a>
        <a href="{{ route('teknisi.index', ['tab' => 'selesai']) }}" class="px-3.5 py-2 rounded-lg transition {{ $status === 'selesai' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }}">
            Siap Diambil ({{ $counts['siap_diambil'] }})
        </a>
    </div>

    <!-- Cards Grid Work Order -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($tickets as $t)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition p-5 flex flex-col justify-between">
                
                <div>
                    <!-- Top Badge & Code -->
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <span class="font-extrabold text-sky-600 text-sm tracking-wide">{{ $t->service_code }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $t->getStatusBadge() }}">
                            {{ $t->getStatusLabel() }}
                        </span>
                    </div>

                    <!-- Laptop Title -->
                    <h3 class="text-base font-bold text-slate-900 leading-snug">{{ $t->laptop_brand }} {{ $t->laptop_model }}</h3>
                    <div class="text-xs text-slate-500 mt-0.5">
                        Pelanggan: <strong class="text-slate-700">{{ $t->customer_name }}</strong> &bull; Masuk: {{ $t->created_at->format('d/m/Y') }}
                    </div>

                    <!-- Keluhan Box -->
                    <div class="mt-3 bg-slate-50 p-2.5 rounded-lg border border-slate-200 text-xs">
                        <span class="font-bold text-slate-500 block text-[10px] uppercase">Keluhan Awal:</span>
                        <p class="text-slate-800 line-clamp-2 mt-0.5 font-medium">{{ $t->initial_complaint }}</p>
                    </div>

                    <!-- Diagnosa Preview -->
                    <div class="mt-2.5 text-xs">
                        <span class="font-bold text-slate-400 block text-[10px] uppercase">Diagnosa Terkini:</span>
                        <p class="text-slate-700 line-clamp-2 mt-0.5 italic">
                            {{ $t->diagnosis ?: 'Belum ada diagnosa teknisi.' }}
                        </p>
                    </div>

                    @if($t->total_cost > 0)
                        <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500">Estimasi Biaya:</span>
                            <span class="font-extrabold text-slate-900">{{ $t->formatRupiah($t->total_cost) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Action Button -->
                <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <span class="text-[11px] text-slate-400">Teknisi: {{ $t->technician_name ?: '-' }}</span>
                    <a href="{{ route('teknisi.edit', $t->service_code) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs shadow-sm transition active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Buka Work Order</span>
                    </a>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 italic">
                Tidak ada tiket servis dalam antrean tab ini.
            </div>
        @endforelse
    </div>

    @if($tickets->hasPages())
        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
    @endif

</div>
@endsection
