@extends('layouts.app')

@section('title', 'Work Order ' . $ticket->service_code . ' - Teknisi TMC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6 pb-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teknisi.index') }}" class="text-xs font-bold text-sky-600 hover:underline">&larr; Kembali ke Antrean</a>
                <span class="text-slate-300">&bull;</span>
                <span class="text-xs font-bold uppercase {{ $ticket->getStatusBadge() }} px-2 py-0.5 rounded">
                    Status: {{ $ticket->getStatusLabel() }}
                </span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">
                Work Order & Diagnosa: {{ $ticket->service_code }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500">
                Unit: <strong>{{ $ticket->laptop_brand }} {{ $ticket->laptop_model }}</strong> &bull; Pemilik: <strong>{{ $ticket->customer_name }}</strong> ({{ $ticket->customer_phone }})
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('tracking.index', ['kode' => $ticket->service_code]) }}" target="_blank" class="px-3.5 py-2 rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-bold shadow-sm">
                Buka Live Tracking &nearr;
            </a>
        </div>
    </div>

    <!-- Ringkasan Unit & Keluhan Awal -->
    <div class="bg-slate-100 rounded-2xl p-5 border border-slate-200 mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
        <div>
            <span class="text-slate-400 font-bold block uppercase text-[10px]">Kelengkapan:</span>
            <span class="text-slate-800 font-medium">{{ $ticket->completeness ?: 'Unit saja' }}</span>
        </div>
        <div>
            <span class="text-slate-400 font-bold block uppercase text-[10px]">Password Testing:</span>
            <span class="font-mono font-bold text-slate-800 bg-white px-2 py-0.5 rounded border border-slate-300 inline-block">{{ $ticket->laptop_password ?: 'Tidak ada' }}</span>
        </div>
        <div>
            <span class="text-slate-400 font-bold block uppercase text-[10px]">Kondisi Fisik:</span>
            <span class="text-slate-800 font-medium">{{ $ticket->physical_condition ?: '-' }}</span>
        </div>
        <div class="sm:col-span-3 pt-2 border-t border-slate-200">
            <span class="text-slate-400 font-bold block uppercase text-[10px] mb-0.5">Keluhan Awal dari Meja Kasir:</span>
            <p class="text-sm font-semibold text-slate-900 bg-white p-3 rounded-xl border border-slate-200">
                {{ $ticket->initial_complaint }}
            </p>
        </div>
    </div>

    <!-- Form Input Work Order & Diagnosa -->
    <form action="{{ route('teknisi.update', $ticket->service_code) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <!-- 1. Nama Teknisi -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                Nama Teknisi Penanggung Jawab *
            </label>
            <input 
                type="text" 
                name="technician_name" 
                value="{{ old('technician_name', $ticket->technician_name ?: 'Randi Kurniawan (Teknisi)') }}" 
                required 
                class="w-full sm:w-1/2 px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 font-medium"
            >
        </div>

        <!-- 2. Diagnosa Kerusakan -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                Hasil Diagnosa Kerusakan & Analisa Teknis *
            </label>
            <textarea 
                name="diagnosis" 
                rows="3" 
                required 
                placeholder="Jelaskan komponen mana yang rusak, hasil pengukuran multimeter/skema, atau kondisi IC..."
                class="w-full p-3.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
            >{{ old('diagnosis', $ticket->diagnosis) }}</textarea>
        </div>

        <!-- 3. Rincian Sparepart -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                Rincian Kebutuhan Sparepart (Jika Ada)
            </label>
            <textarea 
                name="spareparts_detail" 
                rows="2" 
                placeholder="Contoh: 1x Keyboard Backlit Asus ROG Original + 1x Thermal Grizzly Liquid Metal"
                class="w-full p-3.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
            >{{ old('spareparts_detail', $ticket->spareparts_detail) }}</textarea>
        </div>

        <!-- 4. Rincian Biaya -->
        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4">Perhitungan Estimasi Biaya</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Biaya Sparepart (Rp)</label>
                    <input 
                        type="number" 
                        id="costSparepart"
                        name="cost_sparepart" 
                        value="{{ old('cost_sparepart', $ticket->cost_sparepart) }}" 
                        step="1000"
                        min="0"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 font-semibold"
                        oninput="hitungTotal()"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Biaya Jasa Servis (Rp)</label>
                    <input 
                        type="number" 
                        id="costService"
                        name="cost_service" 
                        value="{{ old('cost_service', $ticket->cost_service) }}" 
                        step="1000"
                        min="0"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 font-semibold"
                        oninput="hitungTotal()"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Total Biaya Perbaikan (Rp)</label>
                    <div id="displayTotal" class="w-full px-3.5 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 font-black text-base">
                        {{ $ticket->formatRupiah($ticket->total_cost) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Update Status Pengerjaan -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                Pembaruan Status Servis *
            </label>
            <select name="status" class="w-full sm:w-2/3 py-2.5 px-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white font-semibold">
                <option value="pengecekan" {{ $ticket->status === 'pengecekan' ? 'selected' : '' }}>
                    🔍 Sedang Dalam Pengecekan / Diagnosa
                </option>
                <option value="menunggu_persetujuan" {{ $ticket->status === 'menunggu_persetujuan' ? 'selected' : '' }}>
                    ⏳ Menunggu Persetujuan Biaya Pelanggan (Kirim Estimasi Online)
                </option>
                <option value="pengerjaan" {{ in_array($ticket->status, ['pengerjaan', 'disetujui']) ? 'selected' : '' }}>
                    ⚙️ Dalam Pengerjaan / Penggantian Komponen
                </option>
                <option value="siap_diambil" {{ $ticket->status === 'siap_diambil' ? 'selected' : '' }}>
                    ✅ Selesai Dikerjakan & Lulus QC (Siap Diambil)
                </option>
                <option value="batal" {{ $ticket->status === 'batal' ? 'selected' : '' }}>
                    ❌ Dibatalkan
                </option>
            </select>
            <p class="text-xs text-slate-400 mt-1">
                * Jika memilih <strong>Menunggu Persetujuan Biaya</strong>, sistem akan membuat link approval online bagi pelanggan (Fitur 4) dan menyiapkan pesan WhatsApp otomatis (Fitur 5).
            </p>
        </div>

        <!-- 6. Catatan Log Pengerjaan -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                Catatan Progres Tambahan (Akan Masuk ke Riwayat Log)
            </label>
            <input 
                type="text" 
                name="work_note" 
                placeholder="Contoh: Menunggu part datang dari distributor / IC baru sudah selesai disolder..."
                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
            >
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
            <span class="text-xs text-slate-400">
                Log pengerjaan akan otomatis terbarui di Live Tracking pelanggan.
            </span>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow transition active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Pembaruan Work Order</span>
            </button>
        </div>

    </form>

</div>

<script>
    function hitungTotal() {
        const sparepart = parseFloat(document.getElementById('costSparepart').value) || 0;
        const service = parseFloat(document.getElementById('costService').value) || 0;
        const total = sparepart + service;
        
        document.getElementById('displayTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endsection
