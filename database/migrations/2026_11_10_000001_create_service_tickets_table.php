<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('service_code')->unique();
            
            // Data Pelanggan
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('customer_address')->nullable();
            
            // Spesifikasi Laptop
            $table->string('laptop_brand');
            $table->string('laptop_model');
            $table->string('serial_number')->nullable();
            $table->text('completeness')->nullable(); // Kelengkapan (Unit, Charger, Tas, dll)
            $table->text('initial_complaint'); // Keluhan awal pelanggan
            $table->string('laptop_password')->nullable(); // Password/PIN untuk testing
            $table->text('physical_condition')->nullable(); // Kondisi fisik awal
            
            // Data Diagnosa & Teknisi
            $table->string('technician_name')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('spareparts_detail')->nullable();
            $table->decimal('cost_sparepart', 12, 2)->default(0);
            $table->decimal('cost_service', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            
            // Status Perbaikan
            // antrean, pengecekan, menunggu_persetujuan, disetujui, ditolak, pengerjaan, siap_diambil, selesai, batal
            $table->string('status')->default('antrean');
            
            // Client Approval Online
            $table->string('approval_status')->default('pending'); // pending, approved, rejected
            $table->string('approval_token')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamp('approval_at')->nullable();
            
            // Pembayaran & Kuitansi
            $table->string('payment_status')->default('belum_bayar'); // belum_bayar, lunas
            $table->string('payment_method')->nullable(); // tunai, qris, transfer
            $table->string('payment_reference')->nullable();
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            
            // Garansi & Kasir
            $table->string('warranty_period')->default('30 Hari');
            $table->date('warranty_until')->nullable();
            $table->string('created_by')->default('Admin Kasir TMC');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_tickets');
    }
};
