<?php

namespace App\Http\Controllers;

use App\Models\ServiceLog;
use App\Models\ServiceTicket;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServiceTicketController extends Controller
{
    /**
     * FITUR 1: Dashboard Kasir / Daftar Seluruh Penerimaan Unit
     */
    public function index(Request $request)
    {
        $query = ServiceTicket::query()->with('logs')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('service_code', 'LIKE', "%{$q}%")
                    ->orWhere('customer_name', 'LIKE', "%{$q}%")
                    ->orWhere('customer_phone', 'LIKE', "%{$q}%")
                    ->orWhere('laptop_model', 'LIKE', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $tickets = $query->paginate(10)->withQueryString();

        // Statistik Cepat untuk Baris Header
        $counts = [
            'total' => ServiceTicket::count(),
            'antrean' => ServiceTicket::where('status', 'antrean')->count(),
            'pengerjaan' => ServiceTicket::whereIn('status', ['pengecekan', 'pengerjaan', 'menunggu_persetujuan'])->count(),
            'siap_diambil' => ServiceTicket::where('status', 'siap_diambil')->count(),
            'selesai' => ServiceTicket::where('status', 'selesai')->count(),
        ];

        return view('admin.index', compact('tickets', 'counts'));
    }

    /**
     * FITUR 1: Form Input Penerimaan Unit Servis Baru
     */
    public function create()
    {
        $newCode = ServiceTicket::generateUniqueCode();
        return view('admin.create', compact('newCode'));
    }

    /**
     * FITUR 1: Simpan Penerimaan Unit & Terbitkan Kode Servis
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:150',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:100',
            'customer_address' => 'nullable|string',
            'laptop_brand' => 'required|string|max:50',
            'laptop_model' => 'required|string|max:150',
            'serial_number' => 'nullable|string|max:100',
            'completeness' => 'nullable|string|max:255',
            'initial_complaint' => 'required|string',
            'laptop_password' => 'nullable|string|max:100',
            'physical_condition' => 'nullable|string',
        ]);

        $validated['service_code'] = ServiceTicket::generateUniqueCode();
        $validated['status'] = 'antrean';
        $validated['approval_status'] = 'pending';
        $validated['payment_status'] = 'belum_bayar';
        $validated['created_by'] = 'Kasir Toko TMC';

        $ticket = ServiceTicket::create($validated);

        // Catat Log Penerimaan Pertama
        $waMessage = $ticket->generateWhatsAppMessage('intake');
        ServiceLog::create([
            'service_ticket_id' => $ticket->id,
            'status' => 'antrean',
            'title' => 'Penerimaan Unit Servis Baru',
            'note' => 'Unit laptop berhasil didaftarkan di loket kasir Toko TMC.',
            'actor' => 'Kasir Toko TMC',
            'wa_message' => $waMessage,
        ]);

        return redirect()->route('admin.show', $ticket->service_code)
            ->with('success', "Penerimaan Unit {$ticket->service_code} berhasil dicatat!")
            ->with('wa_url', $ticket->getWhatsAppUrl('intake'));
    }

    /**
     * Detail Tiket Servis Lengkap (Admin View)
     */
    public function show(string $code)
    {
        $ticket = ServiceTicket::where('service_code', $code)->with('logs')->firstOrFail();
        return view('admin.show', compact('ticket'));
    }

    /**
     * FITUR 1: Cetak Tanda Terima Servis Masuk (Nota Loket)
     */
    public function tandaTerima(string $code)
    {
        $ticket = ServiceTicket::where('service_code', $code)->firstOrFail();
        return view('admin.tanda-terima', compact('ticket'));
    }

    /**
     * FITUR 6: Modul Pembayaran Kasir (Halaman Antrean Kasir)
     */
    public function pembayaranIndex()
    {
        $siapBayar = ServiceTicket::where('payment_status', 'belum_bayar')
            ->whereIn('status', ['siap_diambil', 'pengerjaan', 'disetujui'])
            ->latest()
            ->get();

        $riwayatLunas = ServiceTicket::where('payment_status', 'lunas')
            ->latest('paid_at')
            ->take(15)
            ->get();

        return view('admin.pembayaran', compact('siapBayar', 'riwayatLunas'));
    }

    /**
     * FITUR 6: Verifikasi Pembayaran & Ubah Status Menjadi Lunas
     */
    public function prosesPembayaran(Request $request, string $code)
    {
        $ticket = ServiceTicket::where('service_code', $code)->firstOrFail();

        $validated = $request->validate([
            'payment_method' => 'required|in:tunai,qris,transfer',
            'paid_amount' => 'required|numeric|min:0',
            'payment_reference' => 'nullable|string|max:100',
            'warranty_period' => 'required|string|max:50',
        ]);

        $days = match ($validated['warranty_period']) {
            '14 Hari' => 14,
            '30 Hari' => 30,
            '60 Hari' => 60,
            '90 Hari' => 90,
            default => 30,
        };

        $ticket->update([
            'payment_status' => 'lunas',
            'payment_method' => $validated['payment_method'],
            'paid_amount' => $validated['paid_amount'],
            'payment_reference' => $validated['payment_reference'],
            'paid_at' => now(),
            'status' => 'selesai',
            'warranty_period' => $validated['warranty_period'],
            'warranty_until' => now()->addDays($days),
        ]);

        // Catat Log Pembayaran
        $waMessage = $ticket->generateWhatsAppMessage('paid');
        ServiceLog::create([
            'service_ticket_id' => $ticket->id,
            'status' => 'selesai',
            'title' => 'Pembayaran Terverifikasi (LUNAS)',
            'note' => "Pembayaran sebesar {$ticket->formatRupiah($ticket->paid_amount)} via {$validated['payment_method']} telah diverifikasi kasir. Unit dinyatakan selesai dan diserahkan.",
            'actor' => 'Kasir Toko TMC',
            'wa_message' => $waMessage,
        ]);

        return redirect()->route('admin.kuitansi', $ticket->service_code)
            ->with('success', "Pembayaran untuk {$ticket->service_code} berhasil diverifikasi!")
            ->with('wa_url', $ticket->getWhatsAppUrl('paid'));
    }

    /**
     * FITUR 6: Penerbitan Kuitansi Digital Resmi (Printable PDF / Format Faktur)
     */
    public function kuitansiDigital(string $code)
    {
        $ticket = ServiceTicket::where('service_code', $code)->firstOrFail();
        return view('admin.kuitansi', compact('ticket'));
    }

    /**
     * FITUR 7: Pengelolaan Laporan Rekapitulasi (Admin / Owner TMC)
     */
    public function laporanIndex(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $status = $request->get('status');

        $query = ServiceTicket::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($status) {
            $query->where('status', $status);
        }

        $records = $query->orderBy('created_at', 'asc')->get();

        // Rekapitulasi Metrik Keuangan
        $totalPenerimaan = $records->count();
        $totalLunas = $records->where('payment_status', 'lunas')->count();
        $totalBelumLunas = $records->where('payment_status', 'belum_bayar')->count();
        
        $totalOmset = $records->where('payment_status', 'lunas')->sum('paid_amount');
        $estimasiPiutang = $records->where('payment_status', 'belum_bayar')->sum('total_cost');
        $totalBiayaSparepart = $records->sum('cost_sparepart');
        $totalBiayaJasa = $records->sum('cost_service');

        return view('admin.laporan', compact(
            'records',
            'startDate',
            'endDate',
            'status',
            'totalPenerimaan',
            'totalLunas',
            'totalBelumLunas',
            'totalOmset',
            'estimasiPiutang',
            'totalBiayaSparepart',
            'totalBiayaJasa'
        ));
    }

    /**
     * FITUR 7: Export Laporan Rekapitulasi ke Excel/CSV
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $records = ServiceTicket::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('id', 'asc')
            ->get();

        $fileName = "Laporan_Servis_TMC_{$startDate}_sampai_{$endDate}.csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        return response()->stream(function () use ($records) {
            $file = fopen('php://output', 'w');
            
            // BOM UTF-8 untuk Excel
            fputs($file, "\xEF\xBB\xBF");

            // Header Kolom
            fputcsv($file, [
                'No',
                'Kode Servis',
                'Tanggal Masuk',
                'Nama Pelanggan',
                'No WhatsApp',
                'Merk / Model Laptop',
                'Keluhan Awal',
                'Diagnosa Teknisi',
                'Sparepart',
                'Biaya Sparepart (Rp)',
                'Biaya Jasa (Rp)',
                'Total Biaya (Rp)',
                'Status Pengerjaan',
                'Status Pembayaran',
                'Metode Bayar',
                'Tanggal Lunas',
                'Masa Garansi'
            ]);

            $no = 1;
            foreach ($records as $row) {
                fputcsv($file, [
                    $no++,
                    $row->service_code,
                    $row->created_at->format('d/m/Y H:i'),
                    $row->customer_name,
                    $row->customer_phone,
                    $row->laptop_brand . ' ' . $row->laptop_model,
                    $row->initial_complaint,
                    $row->diagnosis ?: '-',
                    $row->spareparts_detail ?: '-',
                    $row->cost_sparepart,
                    $row->cost_service,
                    $row->total_cost,
                    $row->getStatusLabel(),
                    ucfirst($row->payment_status),
                    $row->payment_method ? strtoupper($row->payment_method) : '-',
                    $row->paid_at ? $row->paid_at->format('d/m/Y H:i') : '-',
                    $row->warranty_period
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    /**
     * FITUR 7: Cetak Laporan Rekapitulasi Resmi (Printable PDF View)
     */
    public function cetakLaporan(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $records = ServiceTicket::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('id', 'asc')
            ->get();

        $totalOmset = $records->where('payment_status', 'lunas')->sum('paid_amount');
        $totalLunas = $records->where('payment_status', 'lunas')->count();

        return view('admin.cetak-laporan', compact('records', 'startDate', 'endDate', 'totalOmset', 'totalLunas'));
    }
}
