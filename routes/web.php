<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Operator\RunQueryController;
use App\Http\Controllers\Operator\ManageDatabaseController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin/v1')->group(function(){
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::prefix('operator/v1')->group(function(){
    // Menu Run Query
    Route::get('/runQuery', [RunQueryController::class, 'index'])->name('viewQuery');
    Route::post('/runQuery', [RunQueryController::class, 'executeQuery'])->name('executeQuery');
    
    // Menu Manage Database
    Route::get('/manageDatabase', [ManageDatabaseController::class, 'index'])->name('viewDatabase');
    Route::post('/manageDatabase', [ManageDatabaseController::class, 'store'])->name('addDatabase');
    Route::put('/manageDatabase/edit/{id}', [ManageDatabaseController::class, 'update'])->name('editDatabase');
    Route::put('/manageDatabase/delete/{id}', [ManageDatabaseController::class, 'destroy'])->name('deleteDatabase');
});


Route::get('pengguna/ubah-password', [AdminUserController::class, 'ubahPassword'])->name('pengguna.ubahPassword');
Route::put('pengguna/update-password/{id}', [AdminUserController::class, 'updatePassword'])->name('pengguna.updatePassword');   