@extends('layouts.app')

@section('title', 'Log Notifikasi WhatsApp Bot - Toko TMC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6 pb-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider block">FITUR 5 &bull; AUTOMATION & NOTIFIKASI</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Log Notifikasi & Transaksi WhatsApp Bot
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Riwayat pesan otomatis yang dipicu oleh alur sistem (Tanda Terima, Estimasi & Approval, Siap Diambil, hingga Kuitansi Lunas).
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-300 font-bold text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Bot Service Active (WhatsApp API)</span>
            </span>
        </div>
    </div>

    <!-- Info Box Alur Bot -->
    <div class="bg-gradient-to-r from-emerald-950 to-teal-900 text-white rounded-2xl p-5 shadow-sm mb-6">
        <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-400 mb-2">Alur Trigger Notifikasi Otomatis Bot WhatsApp:</h3>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs text-slate-200">
            <div class="bg-white/10 p-2.5 rounded-lg border border-white/10">
                <strong class="text-white block">1. Penerimaan Loket</strong>
                <span>Kirim bukti tanda terima masuk & link Live Tracking.</span>
            </div>
            <div class="bg-white/10 p-2.5 rounded-lg border border-white/10">
                <strong class="text-white block">2. Estimasi Biaya</strong>
                <span>Kirim rincian diagnosa & link Client Approval Online.</span>
            </div>
            <div class="bg-white/10 p-2.5 rounded-lg border border-white/10">
                <strong class="text-white block">3. Selesai & QC</strong>
                <span>Kirim info unit siap diambil & petunjuk bayar QRIS.</span>
            </div>
            <div class="bg-white/10 p-2.5 rounded-lg border border-white/10">
                <strong class="text-white block">4. Pelunasan Kasir</strong>
                <span>Kirim bukti lunas, link kuitansi PDF, & masa garansi.</span>
            </div>
        </div>
    </div>

    <!-- Table Log Notifikasi -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Daftar Antrean & Log Pesan WhatsApp Terkirim</h3>
            <span class="text-xs text-slate-400">Total: {{ $logs->total() }} Log</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-bold">
                    <tr>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Kode Servis</th>
                        <th class="py-3 px-4">Penerima & No WA</th>
                        <th class="py-3 px-4">Jenis Notifikasi</th>
                        <th class="py-3 px-4">Isi Pesan WhatsApp</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap text-xs text-slate-400">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-sky-600 whitespace-nowrap">
                                <a href="{{ route('admin.show', $log->ticket->service_code) }}" class="hover:underline">
                                    {{ $log->ticket->service_code }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block">{{ $log->ticket->customer_name }}</span>
                                <span class="text-xs text-emerald-600 font-semibold">{{ $log->ticket->customer_phone }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $log->title }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 max-w-md">
                                <div class="bg-slate-50 p-2 rounded-lg border border-slate-200 text-slate-700 font-mono text-[11px] whitespace-pre-line line-clamp-3">
                                    {{ $log->wa_message }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <a 
                                    href="https://api.whatsapp.com/send?phone={{ $log->ticket->getCleanPhone() }}&text={{ urlencode($log->wa_message) }}" 
                                    target="_blank" 
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow transition active:scale-95"
                                >
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.07L2 22l5.07-1.34C8.5 21.52 10.2 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
                                    <span>Buka di WA</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 italic">Belum ada riwayat notifikasi WhatsApp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
