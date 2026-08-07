<?php

use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('countries', CountryController::class)->except(['show']);

        Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
        Route::delete('/submissions/{submission}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');
        Route::get('/submissions/attachments/{attachment}/download', [SubmissionController::class, 'downloadAttachment'])->name('submissions.attachments.download');
        Route::get('/submissions/attachments/{attachment}/view', [SubmissionController::class, 'viewAttachment'])->name('submissions.attachments.view');

        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::put('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status');
        Route::get('/applications/documents/{document}/download', [ApplicationController::class, 'downloadDocument'])->name('applications.documents.download');
        Route::get('/applications/documents/{document}/view', [ApplicationController::class, 'viewDocument'])->name('applications.documents.view');
        Route::get('/applications/{application}/receipt/download', [ApplicationController::class, 'downloadReceipt'])->name('applications.receipt.download');
        Route::get('/applications/{application}/receipt/view', [ApplicationController::class, 'viewReceipt'])->name('applications.receipt.view');
    });
});
