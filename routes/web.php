<?php

use App\Http\Controllers\PublicTrackingController;
use App\Http\Controllers\ServiceTicketController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\WhatsAppBotController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Servis Laptop Toko TMC
|--------------------------------------------------------------------------
*/

// ==================== FITUR 2: PELACAKAN STATUS REAL-TIME (PUBLIK) ====================
Route::get('/', [PublicTrackingController::class, 'index'])->name('home');
Route::get('/tracking', [PublicTrackingController::class, 'index'])->name('tracking.index');

// ==================== FITUR 4: KONFIRMASI PERSETUJUAN BIAYA ONLINE ====================
Route::get('/approval/{code}', [PublicTrackingController::class, 'approvalPage'])->name('tracking.approval');
Route::post('/approval/{code}', [PublicTrackingController::class, 'submitApproval'])->name('tracking.approval.submit');

// Cetak Tanda Terima & Kuitansi Publik / Pelanggan
Route::get('/tanda-terima/{code}', [ServiceTicketController::class, 'tandaTerima'])->name('public.tanda-terima');
Route::get('/kuitansi/{code}', [ServiceTicketController::class, 'kuitansiDigital'])->name('public.kuitansi');

// ==================== FITUR 1, 6, 7: MODUL ADMIN & KASIR TOKO TMC ====================
Route::prefix('admin')->name('admin.')->group(function () {
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
Route::prefix('teknisi')->name('teknisi.')->group(function () {
    Route::get('/', [TechnicianController::class, 'index'])->name('index');
    Route::get('/work-order/{code}', [TechnicianController::class, 'edit'])->name('edit');
    Route::post('/work-order/{code}', [TechnicianController::class, 'update'])->name('update');
});
