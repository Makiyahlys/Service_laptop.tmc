<?php

namespace Tests\Feature;

use App\Models\ServiceTicket;
use App\Models\User;
use Database\Seeders\ServiceTicketSeeder;
use Database\Seeders\UserRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceSevenFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected User $kasir;
    protected User $teknisi;
    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserRoleSeeder::class);
        $this->seed(ServiceTicketSeeder::class);

        $this->kasir = User::where('role', 'kasir')->first();
        $this->teknisi = User::where('role', 'teknisi')->first();
        $this->owner = User::where('role', 'owner')->first();
    }

    /**
     * Test Fitur 2: Live Tracking Halaman Publik (Tanpa Login)
     */
    public function test_fitur_2_tracking_page_can_be_accessed(): void
    {
        $response = $this->get('/tracking?kode=TMC-8821');
        $response->assertStatus(200);
        $response->assertSee('TMC-8821');
    }

    /**
     * Test Fitur 1: Admin & Kasir Pencatatan Penerimaan Unit (Login Kasir)
     */
    public function test_fitur_1_admin_can_view_and_create_service(): void
    {
        $indexResponse = $this->actingAs($this->kasir)->get('/admin');
        $indexResponse->assertStatus(200);

        $createResponse = $this->actingAs($this->kasir)->get('/admin/servis/baru');
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Formulir Penerimaan Unit Servis');

        $postResponse = $this->actingAs($this->kasir)->post('/admin/servis', [
            'customer_name' => 'Testing Pelanggan',
            'customer_phone' => '081233445566',
            'customer_email' => 'test@laptop.com',
            'laptop_brand' => 'Dell',
            'laptop_model' => 'XPS 13 9310',
            'initial_complaint' => 'Touchpad macet dan baterai drop.',
        ]);

        $postResponse->assertSessionHas('success');
    }

    /**
     * Test Fitur 3: Work Order Teknisi (Login Teknisi)
     */
    public function test_fitur_3_technician_workbench(): void
    {
        $response = $this->actingAs($this->teknisi)->get('/teknisi');
        $response->assertStatus(200);
        $response->assertSee('Work Order', false);

        $ticket = ServiceTicket::first();
        $woResponse = $this->actingAs($this->teknisi)->get("/teknisi/work-order/{$ticket->service_code}");
        $woResponse->assertStatus(200);
        $woResponse->assertSee($ticket->service_code);
    }

    /**
     * Test Fitur 4: Client Approval Online (Tanpa Login / Publik)
     */
    public function test_fitur_4_client_approval_page(): void
    {
        $ticket = ServiceTicket::where('service_code', 'TMC-202611-0002')->first();
        $response = $this->get("/approval/{$ticket->service_code}");
        $response->assertStatus(200);
        $response->assertSee('Konfirmasi Persetujuan', false);
    }

    /**
     * Test Fitur 5: WhatsApp Bot Log (Login Kasir)
     */
    public function test_fitur_5_whatsapp_bot_log(): void
    {
        $response = $this->actingAs($this->kasir)->get('/admin/whatsapp');
        $response->assertStatus(200);
        $response->assertSee('WhatsApp Bot', false);
    }

    /**
     * Test Fitur 6: Pembayaran & Kuitansi Digital (Login Kasir)
     */
    public function test_fitur_6_payment_and_digital_receipt(): void
    {
        $response = $this->actingAs($this->kasir)->get('/admin/pembayaran');
        $response->assertStatus(200);

        $ticket = ServiceTicket::where('payment_status', 'lunas')->first();
        if ($ticket) {
            $kuitansiResponse = $this->get("/kuitansi/{$ticket->service_code}");
            $kuitansiResponse->assertStatus(200);
            $kuitansiResponse->assertSee('KUITANSI RESMI');
        }
    }

    /**
     * Test Fitur 7: Laporan Rekapitulasi & Export (Login Owner)
     */
    public function test_fitur_7_report_and_export(): void
    {
        $response = $this->actingAs($this->owner)->get('/admin/laporan');
        $response->assertStatus(200);
        $response->assertSee('Laporan Rekapitulasi', false);

        $exportResponse = $this->actingAs($this->owner)->get('/admin/laporan/export-csv');
        $exportResponse->assertStatus(200);

        $cetakResponse = $this->actingAs($this->owner)->get('/admin/laporan/cetak');
        $cetakResponse->assertStatus(200);
    }
}
