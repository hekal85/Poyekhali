<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ملف راوتات مستقل - متطلبش تعديل web.php الأصلي يدويًا
|--------------------------------------------------------------------------
| الملف ده بيتحمّل تلقائيًا لو ضفت السطر التالي داخل routes/web.php:
|
|     require __DIR__.'/visa.php';
|
| (السطر ده بيضيفه سكريبت install.ps1 تلقائيًا لو مالقهوش موجود)
|--------------------------------------------------------------------------
*/

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/countries', [SiteController::class, 'countriesIndex'])->name('countries.index');
Route::get('/countries/{country}', [SiteController::class, 'countryShow'])->name('countries.show');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/locale', [SiteController::class, 'setLocale'])->name('locale.set');
