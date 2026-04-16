<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| DASHBOARD PUBLIC (TV)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [AdminController::class, 'dashboard']);
Route::get('/dashboard/data', [DashboardController::class, 'data']);

/*
|--------------------------------------------------------------------------
| AMBIL ANTRIAN
|--------------------------------------------------------------------------
*/
Route::get('/ambil', [QueueController::class, 'index']);
Route::post('/ambil', [QueueController::class, 'store']);

/*
|--------------------------------------------------------------------------
| AUTH (LOGIN / LOGOUT)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

/*
|--------------------------------------------------------------------------
| OFFICER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:officer'])->group(function () {
    Route::get('/officer', [OfficerController::class, 'index'])->name('officer.index');
    Route::get('/officer/data', [OfficerController::class, 'data']);
    Route::post('/officer/call/{id}', [OfficerController::class, 'call']);
    Route::post('/officer/done/{id}', [OfficerController::class, 'done']);
    Route::post('/officer/reset', [OfficerController::class, 'reset'])->name('officer.reset');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/user', [AdminController::class, 'user'])->name('admin.user');
    Route::post('/admin/user', [AdminController::class, 'storeUser'])->name('admin.user.store');
    Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
    Route::get('/admin/setting', [AdminController::class, 'setting'])->name('admin.setting');
    Route::post('/admin/setting/update', [AdminController::class, 'updateSetting'])->name('admin.setting.update');
    Route::get('/admin/dashboard/stats', [App\Http\Controllers\AdminController::class, 'getDashboardStats']);
});