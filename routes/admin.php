<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    // دخول/خروج (بدون middleware عشان تقدر توصلهم أصلاً)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // كل حاجة تحت السطر ده محتاجة تسجيل دخول + is_admin = true
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('countries', CountryController::class)->except(['show']);

        Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
        Route::delete('/submissions/{submission}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');
    });
});
