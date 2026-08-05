<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SiteController;
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
Route::post('/locale', [SiteController::class, 'setLocale'])->name('locale.set');

require __DIR__ . '/admin.php';
