@extends('layouts.app')

@section('title', 'Laporan Rekapitulasi Servis & Keuangan - Toko TMC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6 pb-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider block">FITUR 7 &bull; EKSEKUTIF & OWNER</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Laporan Rekapitulasi Servis & Pendapatan
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Rekapitulasi riwayat servis, perputaran sparepart, dan analisis keuangan berkala Toko TMC.
            </p>
        </div>

        <!-- Tombol Export Excel & Cetak PDF -->
        <div class="flex items-center gap-2">
            <!-- Export CSV/Excel -->
            <a href="{{ route('admin.laporan.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow transition active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Export Excel (CSV)</span>
            </a>

            <!-- Cetak PDF -->
            <a href="{{ route('admin.laporan.cetak', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs shadow transition active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Rekap (PDF)</span>
            </a>
        </div>
    </div>

    <!-- Filter Periode Tanggal -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6">
        <form action="{{ route('admin.laporan') }}" method="GET" class="flex flex-wrap items-end gap-3 text-xs sm:text-sm">
            <div>
                <label class="block font-bold text-slate-600 mb-1">Dari Tanggal:</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="p-2 rounded-lg border border-slate-300 font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block font-bold text-slate-600 mb-1">Sampai Tanggal:</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="p-2 rounded-lg border border-slate-300 font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg shadow-sm transition">
                    Terapkan Periode
                </button>
            </div>

            <div class="ml-auto text-xs text-slate-400 self-center">
                Periode Terpilih: <strong>{{ date('d/m/Y', strtotime($startDate)) }}</strong> s/d <strong>{{ date('d/m/Y', strtotime($endDate)) }}</strong>
            </div>
        </form>
    </div>

    <!-- Ringkasan KPI Keuangan -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        
        <!-- Total Omset Lunas -->
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-5 rounded-2xl border border-emerald-200 shadow-sm">
            <span class="text-emerald-800 text-xs font-bold uppercase tracking-wider block">Total Omset Kasir (Lunas)</span>
            <span class="text-2xl font-black text-emerald-900 block mt-1">Rp {{ number_format($totalOmset, 0, ',', '.') }}</span>
            <span class="text-[11px] text-emerald-700 mt-1 block">Dari {{ $totalLunas }} unit lunas diserahkan</span>
        </div>

        <!-- Estimasi Piutang -->
        <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 p-5 rounded-2xl border border-amber-200 shadow-sm">
            <span class="text-amber-800 text-xs font-bold uppercase tracking-wider block">Tagihan Belum Lunas</span>
            <span class="text-2xl font-black text-amber-900 block mt-1">Rp {{ number_format($estimasiPiutang, 0, ',', '.') }}</span>
            <span class="text-[11px] text-amber-700 mt-1 block">{{ $totalBelumLunas }} unit belum dibayar</span>
        </div>

        <!-- Biaya Sparepart -->
        <div class="bg-gradient-to-br from-sky-50 to-sky-100/50 p-5 rounded-2xl border border-sky-200 shadow-sm">
            <span class="text-sky-800 text-xs font-bold uppercase tracking-wider block">Perputaran Sparepart</span>
            <span class="text-2xl font-black text-sky-900 block mt-1">Rp {{ number_format($totalBiayaSparepart, 0, ',', '.') }}</span>
            <span class="text-[11px] text-sky-700 mt-1 block">Total biaya modal part</span>
        </div>

        <!-- Total Pendapatan Jasa -->
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100/50 p-5 rounded-2xl border border-indigo-200 shadow-sm">
            <span class="text-indigo-800 text-xs font-bold uppercase tracking-wider block">Nilai Jasa Teknisi</span>
            <span class="text-2xl font-black text-indigo-900 block mt-1">Rp {{ number_format($totalBiayaJasa, 0, ',', '.') }}</span>
            <span class="text-[11px] text-indigo-700 mt-1 block">Total pendapatan murni jasa</span>
        </div>

    </div>

    <!-- Tabel Rekapitulasi Data -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Tabel Rincian Riwayat Servis ({{ $records->count() }} Transaksi)</h3>
            <span class="text-xs text-slate-400">Data terurut berdasarkan tanggal masuk</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="py-3 px-3">No</th>
                        <th class="py-3 px-3">Kode Servis</th>
                        <th class="py-3 px-3">Tgl Masuk</th>
                        <th class="py-3 px-3">Pelanggan</th>
                        <th class="py-3 px-3">Perangkat Laptop</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3">Sparepart</th>
                        <th class="py-3 px-3">Jasa</th>
                        <th class="py-3 px-3">Total Biaya</th>
                        <th class="py-3 px-3">Bayar</th>
                        <th class="py-3 px-3">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($records as $index => $row)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-3 font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-3 font-bold text-sky-600">
                                <a href="{{ route('admin.show', $row->service_code) }}" class="hover:underline">{{ $row->service_code }}</a>
                            </td>
                            <td class="py-3 px-3 text-slate-500 whitespace-nowrap">{{ $row->created_at->format('d/m/Y') }}</td>
                            <td class="py-3 px-3 font-medium text-slate-800">{{ $row->customer_name }}</td>
                            <td class="py-3 px-3">{{ $row->laptop_brand }} {{ $row->laptop_model }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $row->getStatusBadge() }}">
                                    {{ $row->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-medium">{{ $row->formatRupiah($row->cost_sparepart) }}</td>
                            <td class="py-3 px-3 font-medium">{{ $row->formatRupiah($row->cost_service) }}</td>
                            <td class="py-3 px-3 font-extrabold text-slate-900">{{ $row->formatRupiah($row->total_cost) }}</td>
                            <td class="py-3 px-3">
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $row->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $row->payment_status === 'lunas' ? 'LUNAS' : 'BELUM' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 uppercase text-[11px] font-semibold text-slate-500">{{ $row->payment_method ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-8 text-center text-slate-400 italic">Tidak ada transaksi pada periode yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
