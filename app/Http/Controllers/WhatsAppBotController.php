<?php

namespace App\Http\Controllers;

use App\Models\ServiceLog;
use App\Models\ServiceTicket;
use Illuminate\Http\Request;

class WhatsAppBotController extends Controller
{
    /**
     * FITUR 5: Pusat Notifikasi & Log Pengiriman WhatsApp Bot
     */
    public function index(Request $request)
    {
        $logs = ServiceLog::whereNotNull('wa_message')
            ->with('ticket')
            ->latest()
            ->paginate(15);

        return view('admin.whatsapp-log', compact('logs'));
    }

    /**
     * FITUR 5: Memicu Pengiriman Pesan WhatsApp Manual (Kirim Ulang / Buka WA)
     */
    public function send(Request $request, string $code)
    {
        $ticket = ServiceTicket::where('service_code', $code)->firstOrFail();
        $type = $request->get('type', 'intake');

        $waUrl = $ticket->getWhatsAppUrl($type);

        return redirect()->away($waUrl);
    }
}
