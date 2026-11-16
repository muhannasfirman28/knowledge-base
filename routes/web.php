<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PortfolioController;
use App\Models\Tenancy;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get("/syarat-ketentuan", [HomeController::class, 'term'])->name('terms');
Route::get('/location/{group}', [HomeController::class, 'detail'])->name('group.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::post('/check', [HomeController::class, 'check'])->name('check');

Route::get('/admin/tenancies/{tenancy}/print-qr', function (Tenancy $tenancy) {
    return view('filament.pages.print-qr', compact('tenancy'));
})->name('tenancy.print-qr');


Route::get('/payment/{tenancy}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/cancel/{transaction}', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
Route::post('/midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');


// Personal portfolio (single page, bilingual).
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio')->middleware('locale');
Route::get('/portfolio/project/{key}', [PortfolioController::class, 'projectDetail'])->name('portfolio.project');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/locale/{locale}', [LanguageController::class, 'switch'])->name('locale.switch');


// Admin area (portfolio content management).
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login')->middleware('guest');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post')->middleware('guest');
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('/projects', \App\Http\Controllers\Admin\ProjectController::class);
        Route::resource('/certificates', \App\Http\Controllers\Admin\CertificateController::class);

        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    });
});