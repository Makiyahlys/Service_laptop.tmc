@extends('layouts.app')

@section('title', 'Login Pengguna - Sistem Servis TMC Laptop')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
        
        <!-- Header Branding Card -->
        <div class="text-center">
            <div class="w-16 h-16 rounded-2xl bg-sky-600 text-white flex items-center justify-center mx-auto shadow-lg shadow-sky-600/30 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Portal Masuk Sistem</h2>
            <p class="text-xs text-slate-500 mt-1">Sistem Manajemen Servis & Tracking Real-Time Toko TMC</p>
        </div>

        <!-- Main Login Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xl p-6 sm:p-8">
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" required value="{{ old('email', 'kasir@tmc.com') }}"
                               placeholder="nama@tmc.com"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition @error('email') border-rose-500 @enderror">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                        <span class="text-[11px] text-slate-400">Default: password123</span>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required value="password123"
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition @error('password') border-rose-500 @enderror">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Public Tracking Link -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-sky-600 rounded border-slate-300 focus:ring-sky-500" checked>
                        <span class="text-xs text-slate-600 font-medium">Ingat Saya</span>
                    </label>
                    <a href="{{ route('tracking.index') }}" class="text-xs text-sky-600 hover:text-sky-800 font-semibold hover:underline">
                        Pelanggan? Lacak Servis &rarr;
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-2.5 px-4 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-md shadow-sky-600/25 transition active:scale-[0.99] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>Masuk ke Sistem</span>
                </button>
            </form>

            <!-- Separator Demo Mode -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white px-3 text-slate-400 font-bold tracking-wider">Akses Cepat Pengujian (1-Click)</span>
                </div>
            </div>

            <!-- One-Click Demo Role Switcher (Sangat Membantu Saat Ujian Tugas Akhir) -->
            <div class="space-y-2.5">
                <p class="text-[11px] text-slate-500 text-center font-medium">Pilih peran di bawah ini untuk demonstrasi langsung tanpa ketik sandi:</p>
                
                <div class="grid grid-cols-1 gap-2">
                    <!-- Demo Kasir -->
                    <form action="{{ route('login.quick', ['role' => 'kasir']) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between p-2.5 rounded-xl border border-sky-200 bg-sky-50/70 hover:bg-sky-100 hover:border-sky-300 transition text-left group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-sky-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 group-hover:scale-105 transition">
                                    🛒
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-sky-950">1. Kasir / Front Office</div>
                                    <div class="text-[10px] text-sky-700">kasir@tmc.com &bull; Penerimaan Unit & Pembayaran</div>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-white border border-sky-300 text-sky-700 group-hover:bg-sky-600 group-hover:text-white transition">Masuk &rarr;</span>
                        </button>
                    </form>

                    <!-- Demo Teknisi -->
                    <form action="{{ route('login.quick', ['role' => 'teknisi']) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between p-2.5 rounded-xl border border-amber-200 bg-amber-50/70 hover:bg-amber-100 hover:border-amber-300 transition text-left group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 group-hover:scale-105 transition">
                                    🔧
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-amber-950">2. Teknisi Laptop</div>
                                    <div class="text-[10px] text-amber-700">teknisi@tmc.com &bull; Work Order & Diagnosa</div>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-white border border-amber-300 text-amber-700 group-hover:bg-amber-600 group-hover:text-white transition">Masuk &rarr;</span>
                        </button>
                    </form>

                    <!-- Demo Owner -->
                    <form action="{{ route('login.quick', ['role' => 'owner']) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between p-2.5 rounded-xl border border-purple-200 bg-purple-50/70 hover:bg-purple-100 hover:border-purple-300 transition text-left group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-purple-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 group-hover:scale-105 transition">
                                    👑
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-purple-950">3. Owner / Pimpinan</div>
                                    <div class="text-[10px] text-purple-700">owner@tmc.com &bull; Rekap Omset & Laporan Eksekutif</div>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-white border border-purple-300 text-purple-700 group-hover:bg-purple-600 group-hover:text-white transition">Masuk &rarr;</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Footer Notice -->
        <div class="text-center text-xs text-slate-400">
            &bull; Akun publik/pelanggan tidak perlu login (langsung masukkan kode servis di halaman <a href="{{ route('tracking.index') }}" class="text-sky-600 underline font-semibold">Tracking</a>).
        </div>

    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.add('text-sky-600');
        } else {
            input.type = 'password';
            icon.classList.remove('text-sky-600');
        }
    }
</script>
@endsection
