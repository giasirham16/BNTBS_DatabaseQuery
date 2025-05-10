<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Checker\ApprovalQueryController as CheckerQueryController;
use App\Http\Controllers\Supervisor\ApprovalQueryController as SupervisorQueryController;
use App\Http\Controllers\Operator\RunQueryController;
use App\Http\Controllers\Operator\ManageDatabaseController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin/v1')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Menu operator
Route::prefix('operator/v1')->group(function () {
    // Menu Run Query
    Route::get('/runQuery', [RunQueryController::class, 'index'])->name('viewQuery');
    Route::post('/runQuery', [RunQueryController::class, 'executeQuery'])->name('executeQuery');

    // Menu Manage Database
    Route::get('/manageDatabase', [ManageDatabaseController::class, 'index'])->name('viewDatabase');
    Route::post('/manageDatabase', [ManageDatabaseController::class, 'store'])->name('addDatabase');
    Route::put('/manageDatabase/edit/{id}', [ManageDatabaseController::class, 'update'])->name('editDatabase');
    Route::put('/manageDatabase/delete/{id}', [ManageDatabaseController::class, 'destroy'])->name('deleteDatabase');
});

// Menu checker
Route::prefix('checker/v1')->group(function () {
    // Menu Approve Query
    Route::get('/approveQuery', [CheckerQueryController::class, 'checkQuery'])->name('chkViewQuery');
    Route::post('/approveQuery', [CheckerQueryController::class, 'approveQuery'])->name('chkApproveQuery');

    // Menu Approve Database
    Route::get('/approveDatabase', [CheckerQueryController::class, 'checkDatabase'])->name('chkViewDatabase');
    Route::post('/approveDatabase', [CheckerQueryController::class, 'approveDatabase'])->name('chkApproveDatabase');
});

// Menu supervisor
Route::prefix('Supervisor/v1')->group(function () {
        // Menu Approve Query
    Route::get('/approveQuery', [SupervisorQueryController::class, 'checkQuery'])->name('spvViewQuery');
    Route::post('/approveQuery', [SupervisorQueryController::class, 'approveQuery'])->name('spvApproveQuery');

    // Menu Approve Database
    Route::get('/approveDatabase', [SupervisorQueryController::class, 'checkDatabase'])->name('spvViewDatabase');
    Route::post('/approveDatabase', [SupervisorQueryController::class, 'approveDatabase'])->name('spvApproveDatabase');
});


Route::get('pengguna/ubah-password', [AdminUserController::class, 'ubahPassword'])->name('pengguna.ubahPassword');
Route::put('pengguna/update-password/{id}', [AdminUserController::class, 'updatePassword'])->name('pengguna.updatePassword');
