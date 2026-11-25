<?php

namespace App\Http\Controllers;

use App\Models\ServiceLog;
use App\Models\ServiceTicket;
use Illuminate\Http\Request;

class PublicTrackingController extends Controller
{
    /**
     * FITUR 2: Halaman Utama & Live Tracking Publik
     */
    public function index(Request $request)
    {
        $ticket = null;
        $searched = false;

        if ($request->filled('kode')) {
            $searched = true;
            $kode = trim($request->kode);
            $ticket = ServiceTicket::where('service_code', $kode)
                ->with('logs')
                ->first();
        }

        return view('tracking.index', compact('ticket', 'searched'));
    }

    /**
     * FITUR 4: Halaman Web Konfirmasi Persetujuan Biaya Online (Client Approval)
     */
    public function approvalPage(string $code)
    {
        $ticket = ServiceTicket::where('service_code', $code)->firstOrFail();
        return view('tracking.approval', compact('ticket'));
    }

    /**
     * FITUR 4: Submit Keputusan Pelanggan (Setuju / Tolak)
     */
    public function submitApproval(Request $request, string $code)
    {
        $ticket = ServiceTicket::where('service_code', $code)->firstOrFail();

        $validated = $request->validate([
            'decision' => 'required|in:setuju,tolak',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validated['decision'] === 'setuju') {
            $ticket->update([
                'approval_status' => 'approved',
                'status' => 'pengerjaan',
                'approval_notes' => $validated['notes'] ?: 'Disetujui oleh pelanggan melalui web approval.',
                'approval_at' => now(),
            ]);

            ServiceLog::create([
                'service_ticket_id' => $ticket->id,
                'status' => 'pengerjaan',
                'title' => 'Estimasi Biaya Disetujui Pelanggan',
                'note' => 'Pelanggan menyetujui estimasi biaya via online portal. Teknisi dapat segera melanjutkan perbaikan.',
                'actor' => 'Pelanggan (Online)',
            ]);

            return redirect()->route('tracking.approval', $ticket->service_code)
                ->with('success', 'Terima kasih! Anda telah menyetujui estimasi perbaikan. Teknisi kami akan segera memproses laptop Anda.');
        } else {
            $ticket->update([
                'approval_status' => 'rejected',
                'status' => 'ditolak',
                'approval_notes' => $validated['notes'] ?: 'Ditolak oleh pelanggan melalui web approval.',
                'approval_at' => now(),
            ]);

            ServiceLog::create([
                'service_ticket_id' => $ticket->id,
                'status' => 'ditolak',
                'title' => 'Estimasi Biaya Ditolak Pelanggan',
                'note' => 'Pelanggan menolak estimasi perbaikan. Alasan/Catatan: ' . ($validated['notes'] ?: 'Tidak ada catatan.'),
                'actor' => 'Pelanggan (Online)',
            ]);

            return redirect()->route('tracking.approval', $ticket->service_code)
                ->with('info', 'Konfirmasi penolakan telah tercatat. Unit akan dipersiapkan untuk diambil kembali tanpa pengerjaan lanjutan.');
        }
    }
}
