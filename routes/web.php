<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;

/* HOME */
Route::get('/', function () {
    return view('welcome');
});

/* ================== DASHBOARD ================== */

Route::get('/dashboard', [AdminController::class, 'dashboard']);
Route::get('/dashboard/data', [DashboardController::class, 'data']);

/* ================== AMBIL ANTRIAN ================== */

Route::get('/ambil', [QueueController::class, 'index']);

// 🔥 bypass CSRF (biar ga 419)
Route::post('/ambil', [QueueController::class, 'store'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

/* ================== AUTH ================== */

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

/* ================== OFFICER ================== */

Route::middleware('auth')->group(function(){

    Route::get('/officer', [OfficerController::class, 'index']);

    // realtime
    Route::get('/officer/data', [OfficerController::class, 'data']);

    // 🔥 FIX: Ubah menjadi POST agar aman dan sesuai dengan Javascript fetch() di Blade
    Route::post('/officer/call/{id}', [OfficerController::class, 'call']);
    Route::post('/officer/done/{id}', [OfficerController::class, 'done']);

});

/* ================== ADMIN ================== */

Route::middleware('auth')->group(function(){

    Route::get('/admin', [AdminController::class, 'index']);

    Route::get('/admin/user', [AdminController::class, 'user']);
    Route::post('/admin/user', [AdminController::class, 'storeUser']);
    Route::get('/admin/delete/{id}', [AdminController::class, 'deleteUser']);

    Route::get('/admin/setting', [AdminController::class, 'setting']);
    Route::post('/admin/setting/update', [AdminController::class, 'updateSetting']);

});