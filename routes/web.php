<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicTrackingController;
use App\Http\Controllers\ServiceTicketController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\WhatsAppBotController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Servis Laptop Toko TMC (Multi-User & Hak Akses)
|--------------------------------------------------------------------------
*/

// ==================== AUTENTIKASI PENGGUNA (LOGIN & LOGOUT) ====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/login/quick/{role}', [AuthController::class, 'quickLogin'])->name('login.quick');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== FITUR 2 & 4: PORTAL PUBLIK & PELANGGAN (BEBAS LOGIN) ====================
Route::get('/', [PublicTrackingController::class, 'index'])->name('home');
Route::get('/tracking', [PublicTrackingController::class, 'index'])->name('tracking.index');

// Persetujuan Estimasi Biaya Online oleh Klien
Route::get('/approval/{code}', [PublicTrackingController::class, 'approvalPage'])->name('tracking.approval');
Route::post('/approval/{code}', [PublicTrackingController::class, 'submitApproval'])->name('tracking.approval.submit');

// Cetak Tanda Terima & Kuitansi Publik / Pelanggan
Route::get('/tanda-terima/{code}', [ServiceTicketController::class, 'tandaTerima'])->name('public.tanda-terima');
Route::get('/kuitansi/{code}', [ServiceTicketController::class, 'kuitansiDigital'])->name('public.kuitansi');

// ==================== FITUR 1, 6, 7: MODUL KASIR & OWNER TOKO TMC ====================
// Hanya dapat diakses oleh Kasir dan Owner
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:kasir,owner'])->group(function () {
    // FITUR 1: Pencatatan Penerimaan Unit Servis
    Route::get('/', [ServiceTicketController::class, 'index'])->name('index');
    Route::get('/servis/baru', [ServiceTicketController::class, 'create'])->name('create');
    Route::post('/servis', [ServiceTicketController::class, 'store'])->name('store');
    Route::get('/servis/{code}', [ServiceTicketController::class, 'show'])->name('show');
    Route::get('/servis/{code}/tanda-terima', [ServiceTicketController::class, 'tandaTerima'])->name('tanda-terima');

    // FITUR 6: Verifikasi Pembayaran & Penerbitan Kuitansi Digital
    Route::get('/pembayaran', [ServiceTicketController::class, 'pembayaranIndex'])->name('pembayaran');
    Route::post('/pembayaran/{code}', [ServiceTicketController::class, 'prosesPembayaran'])->name('pembayaran.proses');
    Route::get('/kuitansi/{code}', [ServiceTicketController::class, 'kuitansiDigital'])->name('kuitansi');

    // FITUR 7: Pengelolaan & Cetak Laporan Rekapitulasi (Export CSV / Cetak PDF)
    Route::get('/laporan', [ServiceTicketController::class, 'laporanIndex'])->name('laporan');
    Route::get('/laporan/export-csv', [ServiceTicketController::class, 'exportCsv'])->name('laporan.export');
    Route::get('/laporan/cetak', [ServiceTicketController::class, 'cetakLaporan'])->name('laporan.cetak');

    // FITUR 5: Notifikasi & Informasi Transaksi Otomatis (WhatsApp Bot)
    Route::get('/whatsapp', [WhatsAppBotController::class, 'index'])->name('whatsapp');
    Route::get('/whatsapp/kirim/{code}', [WhatsAppBotController::class, 'send'])->name('whatsapp.send');
});

// ==================== FITUR 3: MODUL WORK ORDER & DIAGNOSA TEKNISI ====================
// Hanya dapat diakses oleh Teknisi dan Owner
Route::prefix('teknisi')->name('teknisi.')->middleware(['auth', 'role:teknisi,owner'])->group(function () {
    Route::get('/', [TechnicianController::class, 'index'])->name('index');
    Route::get('/work-order/{code}', [TechnicianController::class, 'edit'])->name('edit');
    Route::post('/work-order/{code}', [TechnicianController::class, 'update'])->name('update');
});
