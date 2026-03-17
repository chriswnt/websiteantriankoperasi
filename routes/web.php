<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

/* HOME */
Route::get('/', function () {
    return view('welcome');
});

/* DASHBOARD TV */
Route::get('/dashboard', [AdminController::class, 'dashboard']);

/* AMBIL ANTRIAN */
Route::get('/ambil', [QueueController::class, 'index']);
Route::post('/ambil', [QueueController::class, 'store']);

/* LOGIN */
Route::get('/login', [AuthController::class, 'loginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

/* OFFICER */
Route::get('/officer', [OfficerController::class, 'index'])->middleware('auth');

/* ADMIN */
Route::get('/admin', [AdminController::class, 'index'])->middleware('auth');

/* USER */
Route::get('/admin/user', [AdminController::class, 'user'])->middleware('auth');
Route::post('/admin/user', [AdminController::class, 'storeUser'])->middleware('auth');
Route::get('/admin/delete/{id}', [AdminController::class, 'deleteUser'])->middleware('auth');

/* SETTING */
Route::get('/admin/setting', [AdminController::class, 'setting'])->middleware('auth');
Route::post('/admin/setting/update', [AdminController::class, 'updateSetting'])->middleware('auth');
Route::get('/call/{id}', [OfficerController::class, 'call'])
    ->name('call')
    ->middleware('auth');

Route::get('/done/{id}', [OfficerController::class, 'done'])
    ->name('done')
    ->middleware('auth');
    Route::get('/dashboard-data', [DashboardController::class, 'data']);