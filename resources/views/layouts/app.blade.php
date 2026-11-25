<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TMC Laptop - Sistem Servis & Tracking Real-Time')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                            800: '#0c4a6e',
                            900: '#082f49',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Top Navigation Bar (Sistem Multi-Peran: Publik, Kasir, Teknisi, Laporan) -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-40 no-print shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-sky-600 flex items-center justify-center text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-lg font-extrabold text-slate-900 tracking-tight">TMC <span class="text-sky-600">Laptop</span></span>
                            <span class="hidden sm:block text-[10px] text-slate-400 font-semibold uppercase tracking-wider -mt-1">Service Management</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Module Links (7 Fitur Terintegrasi) -->
                <div class="hidden md:flex items-center gap-1 text-xs font-bold text-slate-600">
                    <a href="{{ route('tracking.index') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('home', 'tracking.*') ? 'bg-sky-50 text-sky-700' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        🔍 Live Tracking
                    </a>
                    <a href="{{ route('admin.index') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.index', 'admin.create', 'admin.show') ? 'bg-sky-50 text-sky-700' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        📝 Kasir & Penerimaan
                    </a>
                    <a href="{{ route('teknisi.index') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('teknisi.*') ? 'bg-sky-50 text-sky-700' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        🔧 Work Order Teknisi
                    </a>
                    <a href="{{ route('admin.pembayaran') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.pembayaran*') ? 'bg-sky-50 text-sky-700' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        💳 Kasir Pembayaran
                    </a>
                    <a href="{{ route('admin.laporan') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.laporan*') ? 'bg-sky-50 text-sky-700' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        📊 Rekap & Laporan
                    </a>
                    <a href="{{ route('admin.whatsapp') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.whatsapp*') ? 'bg-sky-50 text-sky-700' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        🤖 WA Bot Log
                    </a>
                </div>

                <!-- Quick Action: Buat Servis Baru -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs shadow-sm transition active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>+ Unit Baru</span>
                    </a>
                </div>

            </div>
        </div>

        <!-- Mobile Quick Switcher Bar -->
        <div class="md:hidden border-t border-slate-100 px-3 py-2 bg-slate-50 flex items-center justify-around text-[11px] font-semibold text-slate-600 overflow-x-auto">
            <a href="{{ route('tracking.index') }}" class="py-1 px-2 whitespace-nowrap {{ request()->routeIs('home', 'tracking.*') ? 'text-sky-600 font-bold' : '' }}">Tracking</a>
            <a href="{{ route('admin.index') }}" class="py-1 px-2 whitespace-nowrap {{ request()->routeIs('admin.index') ? 'text-sky-600 font-bold' : '' }}">Kasir</a>
            <a href="{{ route('teknisi.index') }}" class="py-1 px-2 whitespace-nowrap {{ request()->routeIs('teknisi.*') ? 'text-sky-600 font-bold' : '' }}">Teknisi</a>
            <a href="{{ route('admin.pembayaran') }}" class="py-1 px-2 whitespace-nowrap {{ request()->routeIs('admin.pembayaran*') ? 'text-sky-600 font-bold' : '' }}">Bayar</a>
            <a href="{{ route('admin.laporan') }}" class="py-1 px-2 whitespace-nowrap {{ request()->routeIs('admin.laporan*') ? 'text-sky-600 font-bold' : '' }}">Laporan</a>
        </div>
    </nav>

    <!-- Flash Alert & WhatsApp Notification Popup -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 no-print w-full">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm shadow-sm mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                @if(session('wa_url'))
                    <a href="{{ session('wa_url') }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow transition flex-shrink-0">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.07L2 22l5.07-1.34C8.5 21.52 10.2 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
                        <span>Kirim Notifikasi WhatsApp Sekarang</span>
                    </a>
                @endif
            </div>
        @endif

        @if(session('info'))
            <div class="bg-sky-50 border border-sky-300 text-sky-800 px-4 py-3 rounded-xl flex items-center gap-2 text-sm shadow-sm mb-4">
                <svg class="w-5 h-5 text-sky-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('info') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-xl text-sm shadow-sm mb-4">
                <span class="font-bold block mb-1">Terjadi kesalahan pada input:</span>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-xs text-slate-500 no-print mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div>
                <span class="font-bold text-slate-800">TMC Laptop</span> &bull; Sistem Terpadu Penerimaan, Diagnosa, Tracking & Kuitansi Digital
            </div>
            <div class="text-slate-400">
                &copy; {{ date('Y') }} Toko TMC Laptop. All rights reserved.
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
