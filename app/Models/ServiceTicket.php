<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ServiceTicket extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'cost_sparepart' => 'float',
        'cost_service' => 'float',
        'total_cost' => 'float',
        'paid_amount' => 'float',
        'approval_at' => 'datetime',
        'paid_at' => 'datetime',
        'warranty_until' => 'date',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(ServiceLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Auto generate Kode Servis Unik (Format: TMC-YYYYMMDD-XXXX)
     */
    public static function generateUniqueCode(): string
    {
        $prefix = 'TMC-' . date('Ymd') . '-';
        $latest = self::where('service_code', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($latest) {
            $number = (int) substr($latest->service_code, -4) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Label status yang ramah dibaca
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'antrean' => 'Antrean Masuk',
            'pengecekan' => 'Sedang Didiagnosa Teknisi',
            'menunggu_persetujuan' => 'Menunggu Konfirmasi Biaya',
            'disetujui' => 'Disetujui Pelanggan',
            'ditolak' => 'Perbaikan Ditolak Pelanggan',
            'pengerjaan' => 'Dalam Pengerjaan Servis',
            'siap_diambil' => 'Selesai & Siap Diambil',
            'selesai' => 'Unit Sudah Diambil (Selesai)',
            'batal' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Warna badge status (Tailwind)
     */
    public function getStatusBadge(): string
    {
        return match ($this->status) {
            'antrean' => 'bg-slate-100 text-slate-700 border-slate-300',
            'pengecekan' => 'bg-sky-100 text-sky-800 border-sky-300',
            'menunggu_persetujuan' => 'bg-amber-100 text-amber-800 border-amber-300',
            'disetujui' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'ditolak' => 'bg-rose-100 text-rose-800 border-rose-300',
            'pengerjaan' => 'bg-blue-100 text-blue-800 border-blue-300',
            'siap_diambil' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'selesai' => 'bg-emerald-200 text-emerald-900 border-emerald-400',
            'batal' => 'bg-red-100 text-red-800 border-red-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    /**
     * Progress step index (0 sampai 4) untuk live tracking
     */
    public function getProgressStepIndex(): int
    {
        return match ($this->status) {
            'antrean' => 0,
            'pengecekan' => 1,
            'menunggu_persetujuan', 'disetujui', 'ditolak' => 2,
            'pengerjaan' => 3,
            'siap_diambil', 'selesai' => 4,
            default => 1,
        };
    }

    /**
     * Format Rupiah
     */
    public function formatRupiah($value): string
    {
        return 'Rp ' . number_format($value ?? 0, 0, ',', '.');
    }

    /**
     * Format No WhatsApp ke 62xxxx
     */
    public function getCleanPhone(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->customer_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        return $phone;
    }

    /**
     * Menghasilkan teks notifikasi WhatsApp sesuai alur
     */
    public function generateWhatsAppMessage(string $type): string
    {
        $baseUrl = url('/');
        $trackingUrl = url('/tracking?kode=' . $this->service_code);
        $approvalUrl = url('/approval/' . $this->service_code);
        $invoiceUrl = url('/kuitansi/' . $this->service_code);

        return match ($type) {
            'intake' => "Halo *{$this->customer_name}*,\n\nTerima kasih telah mempercayakan perbaikan laptop Anda di *Toko TMC Laptop*.\n\n"
                . "📌 *Detail Penerimaan Unit:*\n"
                . "• Kode Servis: *{$this->service_code}*\n"
                . "• Perangkat: {$this->laptop_brand} {$this->laptop_model}\n"
                . "• Keluhan: {$this->initial_complaint}\n"
                . "• Kelengkapan: " . ($this->completeness ?: 'Unit saja') . "\n\n"
                . "🔍 *Pantau Progres Servis Real-Time:*\n"
                . "{$trackingUrl}\n\n"
                . "Simpan pesan ini sebagai bukti tanda terima resmi Anda.\n_TMC Laptop Customer Service_",

            'estimation' => "Halo *{$this->customer_name}*,\n\nTeknisi kami telah selesai memeriksa laptop Anda (*{$this->service_code}*).\n\n"
                . "🛠️ *Hasil Diagnosa:* {$this->diagnosis}\n"
                . "📦 *Kebutuhan Sparepart:* " . ($this->spareparts_detail ?: 'Tidak ada') . "\n"
                . "💰 *Total Estimasi Biaya:* *{$this->formatRupiah($this->total_cost)}*\n\n"
                . "⚠️ *Persetujuan Diperlukan:*\n"
                . "Mohon klik link berikut untuk *Menyetujui* atau *Menolak* estimasi perbaikan:\n"
                . "👉 {$approvalUrl}\n\n"
                . "Perbaikan akan dilanjutkan segera setelah konfirmasi Anda.\n_TMC Laptop Technical Team_",

            'ready' => "Halo *{$this->customer_name}*,\n\nKabar baik! Servis laptop Anda (*{$this->service_code}*) telah *SELESAI* dan melewati Quality Control.\n\n"
                . "💻 Unit: {$this->laptop_brand} {$this->laptop_model}\n"
                . "💵 Total Biaya: *{$this->formatRupiah($this->total_cost)}*\n\n"
                . "📍 *Pengambilan Unit:*\n"
                . "Unit sudah dapat diambil di Toko TMC Laptop.\n"
                . "Pembayaran dapat dilakukan secara Tunai atau QRIS saat pengambilan.\n\n"
                . "Lihat status akhir di: {$trackingUrl}\n_Toko TMC Laptop_",

            'paid' => "Halo *{$this->customer_name}*,\n\nTerima kasih! Pembayaran servis Anda (*{$this->service_code}*) sebesar *{$this->formatRupiah($this->paid_amount)}* telah kami terima dengan status *LUNAS*.\n\n"
                . "🧾 *Unduh / Cetak Kuitansi Digital Anda:*\n"
                . "👉 {$invoiceUrl}\n\n"
                . "🛡️ *Garansi Servis:* {$this->warranty_period} (berlaku s/d " . ($this->warranty_until ? $this->warranty_until->format('d/m/Y') : '-') . ").\n"
                . "Terima kasih telah mempercayakan perbaikan di Toko TMC Laptop!",

            default => "Halo {$this->customer_name}, update status servis {$this->service_code}: {$this->getStatusLabel()}.\nCek di: {$trackingUrl}"
        };
    }

    /**
     * URL WhatsApp Web / Api
     */
    public function getWhatsAppUrl(string $type): string
    {
        $phone = $this->getCleanPhone();
        $message = urlencode($this->generateWhatsAppMessage($type));
        return "https://api.whatsapp.com/send?phone={$phone}&text={$message}";
    }
}
