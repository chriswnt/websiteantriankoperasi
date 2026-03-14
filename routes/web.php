<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/officer',[OfficerController::class,'index']);
    Route::get('/officer-data',[OfficerController::class,'data']);
    Route::post('/officer/start/{id}/{loket}',[OfficerController::class,'start']);
    Route::post('/officer/finish/{id}',[OfficerController::class,'finish']);

    Route::get('/admin',[AdminController::class,'index']);
    Route::post('/admin/services', [AdminController::class, 'storeService']);
    Route::put('/admin/services/{service}', [AdminController::class, 'updateService']);
    Route::delete('/admin/services/{service}', [AdminController::class, 'destroyService']);
});

Route::get('/', [DashboardController::class,'index']);

Route::get('/ambil-antrian',[QueueController::class,'ambil']);
Route::post('/generate-antrian',[QueueController::class,'generate']);

Route::get('/queue-data',[DashboardController::class,'data']);