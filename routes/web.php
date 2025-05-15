<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Checker\DatabaseController as CheckerDatabaseController;
use App\Http\Controllers\Checker\QueryController as CheckerQueryController;
use App\Http\Controllers\Supervisor\DatabaseController as SupervisorDatabaseController;
use App\Http\Controllers\Supervisor\QueryController as SupervisorQueryController;
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

// Menu superadmin
Route::prefix('superadmin/v1')->group(function () {
    // Menu Manage User
    Route::get('/manageUser', [AdminUserController::class, 'index'])->name('viewUser');
    Route::post('/manageUser', [AdminUserController::class, 'store'])->name('addUser');
    Route::post('/manageUser/approve', [AdminUserController::class, 'approve'])->name('approveUser');
    Route::post('/manageUser/delete', [AdminUserController::class, 'destroy'])->name('deleteUser');
});

// Menu operator
Route::prefix('operator/v1')->group(function () {
    // Menu Run Query
    Route::get('/runQuery', [RunQueryController::class, 'index'])->name('viewQuery');
    Route::post('/runQuery', [RunQueryController::class, 'executeQuery'])->name('executeQuery');

    // Menu Manage Database
    Route::get('/manageDatabase', [ManageDatabaseController::class, 'index'])->name('viewDatabase');
    Route::post('/manageDatabase', [ManageDatabaseController::class, 'store'])->name('addDatabase');
    Route::post('/manageDatabase/edit/', [ManageDatabaseController::class, 'update'])->name('editDatabase');
    Route::post('/manageDatabase/delete/', [ManageDatabaseController::class, 'destroy'])->name('deleteDatabase');
});

// Menu checker
Route::prefix('checker/v1')->group(function () {
    // Menu Approve Query
    Route::get('/approveQuery', [CheckerQueryController::class, 'index'])->name('chkViewQuery');
    Route::post('/approveQuery', [CheckerQueryController::class, 'approveQuery'])->name('chkApproveQuery');

    // Menu Approve Database
    Route::get('/approveDatabase', [CheckerDatabaseController::class, 'index'])->name('chkViewDatabase');
    Route::post('/approveDatabase', [CheckerDatabaseController::class, 'approveDatabase'])->name('chkApproveDatabase');
});

// Menu supervisor
Route::prefix('supervisor/v1')->group(function () {
        // Menu Approve Query
    Route::get('/approveQuery', [SupervisorQueryController::class, 'index'])->name('spvViewQuery');
    Route::post('/approveQuery', [SupervisorQueryController::class, 'approveQuery'])->name('spvApproveQuery');

    // Menu Approve Database
    Route::get('/approveDatabase', [SupervisorDatabaseController::class, 'index'])->name('spvViewDatabase');
    Route::post('/approveDatabase', [SupervisorDatabaseController::class, 'approveDatabase'])->name('spvApproveDatabase');
});


Route::get('pengguna/ubah-password', [AdminUserController::class, 'ubahPassword'])->name('pengguna.ubahPassword');
Route::put('pengguna/update-password/{id}', [AdminUserController::class, 'updatePassword'])->name('pengguna.updatePassword');
