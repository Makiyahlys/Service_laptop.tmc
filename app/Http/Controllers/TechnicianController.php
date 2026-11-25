<?php

namespace App\Http\Controllers;

use App\Models\ServiceLog;
use App\Models\ServiceTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TechnicianController extends Controller
{
    /**
     * FITUR 3: Dashboard Work Order & Antrean Teknisi
     */
    public function index(Request $request)
    {
        $status = $request->get('tab', 'aktif');

        $query = ServiceTicket::query()->latest();

        if ($status === 'antrean') {
            $query->where('status', 'antrean');
        } elseif ($status === 'diagnosa') {
            $query->where('status', 'pengecekan');
        } elseif ($status === 'approval') {
            $query->where('status', 'menunggu_persetujuan');
        } elseif ($status === 'pengerjaan') {
            $query->whereIn('status', ['disetujui', 'pengerjaan']);
        } elseif ($status === 'selesai') {
            $query->whereIn('status', ['siap_diambil', 'selesai']);
        } else {
            // Aktif (semua yang belum selesai)
            $query->whereNotIn('status', ['selesai', 'batal']);
        }

        $tickets = $query->paginate(12)->withQueryString();

        $counts = [
            'total_aktif' => ServiceTicket::whereNotIn('status', ['selesai', 'batal'])->count(),
            'antrean' => ServiceTicket::where('status', 'antrean')->count(),
            'pengecekan' => ServiceTicket::where('status', 'pengecekan')->count(),
            'approval' => ServiceTicket::where('status', 'menunggu_persetujuan')->count(),
            'pengerjaan' => ServiceTicket::whereIn('status', ['disetujui', 'pengerjaan'])->count(),
            'siap_diambil' => ServiceTicket::where('status', 'siap_diambil')->count(),
        ];

        return view('teknisi.index', compact('tickets', 'counts', 'status'));
    }

    /**
     * FITUR 3: Form Work Order & Input Diagnosa Kerusakan
     */
    public function edit(string $code)
    {
        $ticket = ServiceTicket::where('service_code', $code)->with('logs')->firstOrFail();
        return view('teknisi.work-order', compact('ticket'));
    }

    /**
     * FITUR 3: Simpan Hasil Diagnosa, Rincian Sparepart & Update Status Pengerjaan
     */
    public function update(Request $request, string $code)
    {
        $ticket = ServiceTicket::where('service_code', $code)->firstOrFail();

        $validated = $request->validate([
            'technician_name' => 'required|string|max:100',
            'diagnosis' => 'required|string',
            'spareparts_detail' => 'nullable|string',
            'cost_sparepart' => 'nullable|numeric|min:0',
            'cost_service' => 'nullable|numeric|min:0',
            'status' => 'required|in:pengecekan,menunggu_persetujuan,pengerjaan,siap_diambil,batal',
            'work_note' => 'nullable|string',
        ]);

        $costSparepart = (float) ($validated['cost_sparepart'] ?? 0);
        $costService = (float) ($validated['cost_service'] ?? 0);
        $totalCost = $costSparepart + $costService;

        $updateData = [
            'technician_name' => $validated['technician_name'],
            'diagnosis' => $validated['diagnosis'],
            'spareparts_detail' => $validated['spareparts_detail'],
            'cost_sparepart' => $costSparepart,
            'cost_service' => $costService,
            'total_cost' => $totalCost,
            'status' => $validated['status'],
        ];

        // Jika status diubah ke menunggu persetujuan pelanggan, buat token approval
        if ($validated['status'] === 'menunggu_persetujuan' && !$ticket->approval_token) {
            $updateData['approval_token'] = Str::random(24);
            $updateData['approval_status'] = 'pending';
        }

        $ticket->update($updateData);

        // Buat Catatan Log
        $logTitle = match ($validated['status']) {
            'pengecekan' => 'Unit Sedang Dalam Pengecekan / Diagnosa',
            'menunggu_persetujuan' => 'Diagnosa Selesai - Estimasi Dikirim ke Pelanggan',
            'pengerjaan' => 'Proses Servis / Penggantian Komponen Sedang Dikerjakan',
            'siap_diambil' => 'Servis Selesai & Lulus QC (Siap Diambil)',
            'batal' => 'Servis Dibatalkan',
            default => 'Pembaruan Work Order'
        };

        $waType = match ($validated['status']) {
            'menunggu_persetujuan' => 'estimation',
            'siap_diambil' => 'ready',
            default => null
        };

        $waMessage = $waType ? $ticket->generateWhatsAppMessage($waType) : null;

        ServiceLog::create([
            'service_ticket_id' => $ticket->id,
            'status' => $validated['status'],
            'title' => $logTitle,
            'note' => $validated['work_note'] ?: "Status diperbarui oleh teknisi {$validated['technician_name']}.",
            'actor' => "Teknisi ({$validated['technician_name']})",
            'wa_message' => $waMessage,
        ]);

        $response = redirect()->route('teknisi.edit', $ticket->service_code)
            ->with('success', "Work Order {$ticket->service_code} berhasil diperbarui!");

        if ($waType) {
            $response->with('wa_url', $ticket->getWhatsAppUrl($waType));
        }

        return $response;
    }
}
