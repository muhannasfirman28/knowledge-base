<?php

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/check', [HomeController::class, 'check'])->name('check');

// 2. Halaman Pembayaran (Detail Tagihan)
Route::get('/payment/{tenancy}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');

// 3. Callback Midtrans (Webhook)
// Midtrans akan "menembak" URL ini saat pembayaran sukses/gagal
Route::post('/midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');