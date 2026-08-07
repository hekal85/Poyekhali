<?php

use App\Http\Controllers\ApplyController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\VisaTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| راوتات الموقع العام - محملة تلقائيًا عبر require __DIR__.'/visa.php' جوه web.php
|--------------------------------------------------------------------------
*/

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/countries', [SiteController::class, 'countriesIndex'])->name('countries.index');
Route::get('/countries/{country}', [SiteController::class, 'countryShow'])->name('countries.show');

Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/apply', [SiteController::class, 'apply'])->name('apply');
Route::post('/apply', [ApplyController::class, 'store'])->name('apply.store');

Route::get('/track', [TrackController::class, 'show'])->name('track');
Route::post('/track', [TrackController::class, 'lookup'])->name('track.lookup');

Route::get('/visa-types/{key}', [VisaTypeController::class, 'show'])->name('visa-types.show');

// ملحوظة: تبديل اللغة بيتم بالكامل من جانب المتصفح (vue-i18n + localStorage)،
// من غير ما نحتاج نبعت أي حاجة للسيرفر.

// دخول/تسجيل العملاء (مختلف عن /admin)
Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
});

// كل حاجة محتاجة تسجيل دخول (عميل عادي - مش أدمن بالضرورة)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
});

require __DIR__ . '/admin.php';
