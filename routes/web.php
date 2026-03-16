<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QueueController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OfficerController;


Route::get('/', [DashboardController::class, 'index']);

Route::get('/ambil', [QueueController::class, 'index']);
Route::post('/ambil', [QueueController::class, 'store']);

Route::get('/officer', [OfficerController::class, 'index']);
Route::get('/call/{id}', [OfficerController::class, 'call']);
Route::get('/done/{id}', [OfficerController::class, 'done']);

Route::get('/login', [AuthController::class, 'loginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/menu', function () {
    Route::get('/ambil',[QueueController::class,'index']);
Route::post('/ambil',[QueueController::class,'store']);

Route::get('/officer',[OfficerController::class,'index']);
Route::get('/call/{id}',[OfficerController::class,'call']);

Route::get('/',[DashboardController::class,'index']);
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OfficerController;
    return view('welcome');
}); 