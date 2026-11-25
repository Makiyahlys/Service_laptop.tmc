<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationAndRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserRoleSeeder::class);
    }

    /**
     * Tamu / Pengunjung tidak bisa membuka modul admin atau teknisi tanpa login.
     */
    public function test_guests_are_redirected_to_login(): void
    {
        $adminResponse = $this->get('/admin');
        $adminResponse->assertRedirect('/login');

        $teknisiResponse = $this->get('/teknisi');
        $teknisiResponse->assertRedirect('/login');
    }

    /**
     * Formulir halaman login dapat diakses dengan baik.
     */
    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Portal Masuk Sistem');
        $response->assertSee('kasir@tmc.com');
        $response->assertSee('teknisi@tmc.com');
        $response->assertSee('owner@tmc.com');
    }

    /**
     * Kasir berhasil login dan diarahkan ke dashboard kasir (/admin).
     */
    public function test_kasir_can_login_and_access_admin_portal(): void
    {
        $response = $this->post('/login', [
            'email' => 'kasir@tmc.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin');

        // Kasir dapat membuka modul kasir
        $this->get('/admin')->assertStatus(200);

        // Kasir DITOLAK saat mencoba membuka modul teknisi
        $deniedResponse = $this->get('/teknisi');
        $deniedResponse->assertRedirect('/admin');
        $deniedResponse->assertSessionHas('error');
    }

    /**
     * Teknisi berhasil login dan diarahkan ke dashboard teknisi (/teknisi).
     */
    public function test_teknisi_can_login_and_access_workbench(): void
    {
        $response = $this->post('/login', [
            'email' => 'teknisi@tmc.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/teknisi');

        // Teknisi dapat membuka modul teknisi
        $this->get('/teknisi')->assertStatus(200);

        // Teknisi DITOLAK saat mencoba membuka modul admin / kasir
        $deniedResponse = $this->get('/admin');
        $deniedResponse->assertRedirect('/teknisi');
        $deniedResponse->assertSessionHas('error');
    }

    /**
     * Owner memiliki akses ke laporan, admin, dan modul teknisi.
     */
    public function test_owner_can_access_all_modules(): void
    {
        $response = $this->post('/login', [
            'email' => 'owner@tmc.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/laporan');

        $this->get('/admin/laporan')->assertStatus(200);
        $this->get('/admin')->assertStatus(200);
        $this->get('/teknisi')->assertStatus(200);
    }

    /**
     * Fitur One-Click Quick Login demo bekerja dengan baik untuk semua peran.
     */
    public function test_one_click_quick_login_works(): void
    {
        $quickKasir = $this->post('/login/quick/kasir');
        $this->assertAuthenticated();
        $quickKasir->assertRedirect('/admin');

        $this->post('/logout');
        $this->assertGuest();

        $quickTeknisi = $this->post('/login/quick/teknisi');
        $this->assertAuthenticated();
        $quickTeknisi->assertRedirect('/teknisi');

        $this->post('/logout');
        $this->assertGuest();

        $quickOwner = $this->post('/login/quick/owner');
        $this->assertAuthenticated();
        $quickOwner->assertRedirect('/admin/laporan');
    }

    /**
     * Login gagal jika password salah.
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'kasir@tmc.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Logout membersihkan sesi autentikasi dan diarahkan ke halaman login.
     */
    public function test_user_can_logout(): void
    {
        $user = User::where('role', 'kasir')->first();
        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
    }
}
