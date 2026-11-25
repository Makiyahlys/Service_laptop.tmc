<?php

namespace Database\Seeders;

use App\Models\ServiceLog;
use App\Models\ServiceTicket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceTicketSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Antrean Baru (Belum diperiksa)
        $t1 = ServiceTicket::create([
            'service_code' => 'TMC-202611-0001',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081298765432',
            'customer_email' => 'budi.santoso@gmail.com',
            'customer_address' => 'Jl. Merdeka No. 45, Jakarta Pusat',
            'laptop_brand' => 'HP',
            'laptop_model' => 'Pavilion 14-dv0053TX',
            'serial_number' => '5CD1234XYZ',
            'completeness' => 'Unit Laptop + Adaptor Charger Asli + Softcase',
            'initial_complaint' => 'Kipas berisik dan laptop mati sendiri setelah dipakai 15 menit.',
            'laptop_password' => '123456',
            'physical_condition' => 'Baret halus pemakaian normal, layar mulus.',
            'status' => 'antrean',
            'created_by' => 'Kasir Toko TMC',
        ]);
        ServiceLog::create([
            'service_ticket_id' => $t1->id,
            'status' => 'antrean',
            'title' => 'Penerimaan Unit Servis',
            'note' => 'Unit diterima di meja kasir oleh Admin/Kasir Toko TMC.',
            'actor' => 'Kasir',
            'wa_message' => $t1->generateWhatsAppMessage('intake'),
        ]);

        // 2. Menunggu Persetujuan Pelanggan (Client Approval Demo)
        $t2 = ServiceTicket::create([
            'service_code' => 'TMC-202611-0002',
            'customer_name' => 'Citra Lestari',
            'customer_phone' => '081388776655',
            'customer_email' => 'citra.lestari@yahoo.com',
            'customer_address' => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
            'laptop_brand' => 'Acer',
            'laptop_model' => 'Swift 3 SF314-511',
            'serial_number' => 'NXABFSG001',
            'completeness' => 'Unit Laptop + Charger',
            'initial_complaint' => 'Layar bergaris-garis vertikal dan sering kedip saat engsel digerakkan.',
            'laptop_password' => 'citra2026',
            'physical_condition' => 'Sudut kanan bezel bawah sedikit renggang.',
            'technician_name' => 'Randi Kurniawan (Teknisi Senior)',
            'diagnosis' => 'Kabel fleksibel eDP LCD terjepit & panel LCD 14.0 FHD IPS mengalami kerusakan internal pada gate driver.',
            'spareparts_detail' => '1x Panel LCD 14.0" FHD IPS 60Hz 30-Pin + 1x Kabel Fleksibel eDP Acer Swift 3 Original',
            'cost_sparepart' => 650000,
            'cost_service' => 200000,
            'total_cost' => 850000,
            'status' => 'menunggu_persetujuan',
            'approval_status' => 'pending',
            'approval_token' => Str::random(24),
            'created_by' => 'Kasir Toko TMC',
        ]);
        ServiceLog::create([
            'service_ticket_id' => $t2->id,
            'status' => 'menunggu_persetujuan',
            'title' => 'Diagnosa Selesai - Estimasi Dikirim ke Pelanggan',
            'note' => 'Teknisi menginput estimasi biaya Rp 850.000 dan mengirimkan link persetujuan online.',
            'actor' => 'Teknisi (Randi)',
            'wa_message' => $t2->generateWhatsAppMessage('estimation'),
        ]);

        // 3. Sedang Dikerjakan (In Progress)
        $t3 = ServiceTicket::create([
            'service_code' => 'TMC-8821',
            'customer_name' => 'Rian Hidayat',
            'customer_phone' => '081234567890',
            'customer_email' => 'rian.hidayat@gmail.com',
            'customer_address' => 'Komp. Polri Blok B3, Jakarta Timur',
            'laptop_brand' => 'Asus',
            'laptop_model' => 'ROG Zephyrus G14 (GA402)',
            'serial_number' => 'M7N0CX012345',
            'completeness' => 'Unit Only (Charger milik sendiri tipe 240W)',
            'initial_complaint' => 'Overheating hingga 98°C saat main game dan sering restart mendadak.',
            'laptop_password' => 'asusrog2026',
            'physical_condition' => 'Body mulus tidak ada dent.',
            'technician_name' => 'Bambang S. (Spesialis Motherboard)',
            'diagnosis' => 'Liquid metal pabrikan sudah kering & mengoksidasi vapor chamber, thermal pad VRAM mengeras.',
            'spareparts_detail' => 'Thermal Grizzly Conductonaut Liquid Metal + K5 Pro Viscous Thermal Paste VRAM',
            'cost_sparepart' => 250000,
            'cost_service' => 300000,
            'total_cost' => 550000,
            'status' => 'pengerjaan',
            'approval_status' => 'approved',
            'approval_notes' => 'Disetujui pelanggan via web approval pada 09 Nov 2026.',
            'approval_at' => now()->subDay(),
            'created_by' => 'Kasir Toko TMC',
        ]);
        ServiceLog::create([
            'service_ticket_id' => $t3->id,
            'status' => 'pengerjaan',
            'title' => 'Pengerjaan Unit Dimulai',
            'note' => 'Pembersihan vapor chamber dan aplikasi liquid metal baru.',
            'actor' => 'Teknisi (Bambang)',
            'wa_message' => 'Status pengerjaan sedang berlangsung.',
        ]);

        // 4. Siap Diambil (Menunggu Pembayaran)
        $t4 = ServiceTicket::create([
            'service_code' => 'TMC-9902',
            'customer_name' => 'Kevin Pratama',
            'customer_phone' => '085712345678',
            'customer_email' => 'kevin.p@outlook.com',
            'customer_address' => 'Apartemen Mediterania Garden Tower B, Jakarta Barat',
            'laptop_brand' => 'Lenovo',
            'laptop_model' => 'Legion 5 Pro 16ACH6H',
            'serial_number' => 'PF2ABC123',
            'completeness' => 'Unit + Charger 300W',
            'initial_complaint' => 'Layar blank hitam sama sekali tapi tombol power dan lampu keyboard nyala.',
            'laptop_password' => 'kevin99',
            'physical_condition' => 'Mulus terawat.',
            'technician_name' => 'Randi Kurniawan (Teknisi Senior)',
            'diagnosis' => 'Corrupted BIOS chip pada IC SPI Flash karena update Windows mandek.',
            'spareparts_detail' => 'Reprogram / Flash BIOS IC Chip dengan file clean DUMP & re-solder.',
            'cost_sparepart' => 150000,
            'cost_service' => 350000,
            'total_cost' => 500000,
            'status' => 'siap_diambil',
            'approval_status' => 'approved',
            'approval_at' => now()->subDays(2),
            'payment_status' => 'belum_bayar',
            'created_by' => 'Kasir Toko TMC',
        ]);
        ServiceLog::create([
            'service_ticket_id' => $t4->id,
            'status' => 'siap_diambil',
            'title' => 'Servis Selesai & QC Passed',
            'note' => 'Laptop menyala normal, benchmark 30 menit stabil. Notifikasi pengambilan dikirim ke pelanggan.',
            'actor' => 'Teknisi (Randi)',
            'wa_message' => $t4->generateWhatsAppMessage('ready'),
        ]);

        // 5. Selesai & Lunas (Kuitansi Digital Tersedia)
        $t5 = ServiceTicket::create([
            'service_code' => 'TMC-202611-0005',
            'customer_name' => 'Denny Sumargo',
            'customer_phone' => '081122334455',
            'customer_email' => 'densu.official@gmail.com',
            'customer_address' => 'Pondok Indah Bukit Hijau, Jakarta Selatan',
            'laptop_brand' => 'Apple',
            'laptop_model' => 'MacBook Air M1 2020 (A2337)',
            'serial_number' => 'C02G1234Q05D',
            'completeness' => 'Unit Only',
            'initial_complaint' => 'Baterai service recommended, persentase drop dari 80% langsung mati.',
            'laptop_password' => 'densu2026',
            'physical_condition' => 'Baret halus tipis di casing bawah.',
            'technician_name' => 'Bambang S. (Spesialis Motherboard)',
            'diagnosis' => 'Baterai internal mengalami degradasi cycle count 890x, cell baterai bengkak tipis.',
            'spareparts_detail' => '1x Baterai MacBook Air M1 Original OEM (A2389) 49.9Wh',
            'cost_sparepart' => 950000,
            'cost_service' => 300000,
            'total_cost' => 1250000,
            'status' => 'selesai',
            'approval_status' => 'approved',
            'approval_at' => now()->subDays(3),
            'payment_status' => 'lunas',
            'payment_method' => 'qris',
            'payment_reference' => 'QRIS-TMC-8839210',
            'paid_amount' => 1250000,
            'paid_at' => now()->subHours(5),
            'warranty_period' => '90 Hari',
            'warranty_until' => now()->addDays(90),
            'created_by' => 'Kasir Toko TMC',
        ]);
        ServiceLog::create([
            'service_ticket_id' => $t5->id,
            'status' => 'selesai',
            'title' => 'Pembayaran Lunas & Unit Diambil',
            'note' => 'Pembayaran via QRIS berhasil diverifikasi. Kuitansi digital diterbitkan.',
            'actor' => 'Kasir',
            'wa_message' => $t5->generateWhatsAppMessage('paid'),
        ]);
    }
}
