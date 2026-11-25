@extends('layouts.app')

@section('title', 'Penerimaan Unit Servis Baru - Toko TMC Laptop')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6 pb-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider block">FITUR 1 &bull; KASIR LOKET</span>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Formulir Penerimaan Unit Servis</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Input data pelanggan, spesifikasi laptop, dan keluhan awal untuk menerbitkan Kode Servis unik.</p>
        </div>
        <div class="bg-sky-50 border border-sky-200 px-3.5 py-1.5 rounded-xl text-center sm:text-right">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">Kode Servis Otomatis:</span>
            <span class="text-sm font-black text-sky-700 tracking-wider">{{ $newCode }}</span>
        </div>
    </div>

    <!-- Form Input -->
    <form action="{{ route('admin.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <!-- SECTION 1: DATA PELANGGAN -->
        <div>
            <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2 pb-2 border-b border-slate-100 mb-4">
                <span class="w-6 h-6 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center text-xs">1</span>
                <span>Data Pelanggan</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Pelanggan *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Contoh: Bpk. Bambang Pamungkas" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp Aktif * (untuk Bot Notifikasi)</label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Contoh: 081234567890" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Pelanggan (Opsional)</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="Contoh: pelanggan@gmail.com" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Singkat / Domisili</label>
                    <input type="text" name="customer_address" value="{{ old('customer_address') }}" placeholder="Contoh: Jl. Sudirman No. 10, Jakarta" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>
        </div>

        <!-- SECTION 2: SPESIFIKASI & FISIK LAPTOP -->
        <div>
            <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2 pb-2 border-b border-slate-100 mb-4">
                <span class="w-6 h-6 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center text-xs">2</span>
                <span>Spesifikasi & Kondisi Laptop</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Merk Laptop *</label>
                    <input type="text" name="laptop_brand" value="{{ old('laptop_brand') }}" placeholder="Asus / Lenovo / Acer / HP / Dell / Apple" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tipe / Model Laptop *</label>
                    <input type="text" name="laptop_model" value="{{ old('laptop_model') }}" placeholder="Contoh: ROG Strix G15 / Ideapad 3" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Seri / SN (Opsional)</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number') }}" placeholder="Contoh: S/N: M7N0CX..." class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kelengkapan Unit Diterima</label>
                    <input type="text" name="completeness" value="{{ old('completeness', 'Unit Laptop + Charger Asli') }}" placeholder="Contoh: Unit + Charger + Tas + Mouse" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Password / PIN Windows / BIOS (untuk Pengujian)</label>
                    <input type="text" name="laptop_password" value="{{ old('laptop_password') }}" placeholder="Contoh: 1234 atau Tanpa Password" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Kondisi Fisik Awal (Baret/Pecah/Retak)</label>
                    <input type="text" name="physical_condition" value="{{ old('physical_condition', 'Kondisi fisik mulus normal pemakaian.') }}" placeholder="Contoh: Ada baret tipis di casing atas, engsel kiri sedikit renggang" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>
        </div>

        <!-- SECTION 3: KELUHAN AWAL -->
        <div>
            <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2 pb-2 border-b border-slate-100 mb-4">
                <span class="w-6 h-6 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center text-xs">3</span>
                <span>Keluhan & Gejala Kerusakan Awal</span>
            </h2>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Keluhan Pelanggan *</label>
                <textarea 
                    name="initial_complaint" 
                    rows="3" 
                    required 
                    placeholder="Contoh: Laptop mati mendadak saat dipakai game. Lampu indikator tidak menyala saat dicas. Terdengar bunyi tek-tek halus dari area charger." 
                    class="w-full p-3 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                >{{ old('initial_complaint') }}</textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
            <span class="text-xs text-slate-400">
                * Setelah disimpan, sistem akan otomatis mencatat log antrean dan menyiapkan notifikasi WhatsApp bukti terima.
            </span>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.index') }}" class="w-full sm:w-auto px-4 py-2.5 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-bold text-center">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs sm:text-sm shadow-sm transition active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan & Terbitkan Tanda Terima</span>
                </button>
            </div>
        </div>

    </form>

</div>
@endsection
