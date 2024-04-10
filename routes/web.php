<?php

use App\Http\Controllers\Email2Controller;
use App\Http\Controllers\MaintenanceModeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Tool2Controller;
use App\Http\Controllers\User2Controller;
use \App\Http\Controllers\StatisticController;
use Illuminate\Support\Facades\Route;



use Illuminate\Support\Facades\Mail;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::prefix('admin')->group(function () {
// ---------- Role -----------------
Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
// ---------- // Role // ------------

// ---------- Benutzer -----------------
Route::get('/users', [User2Controller::class, 'index'])->name('users.index');
Route::get('/users/create', [User2Controller::class, 'create'])->name('users.create');
Route::post('/users', [User2Controller::class, 'store'])->name('users.store');
Route::get('/users/{id}', [User2Controller::class, 'show'])->name('users.show');
Route::get('/users/{id}/edit', [User2Controller::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [User2Controller::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [User2Controller::class, 'destroy'])->name('users.destroy');
// ---------- // Benutzer // ------------

Route::get('/tools', [Tool2Controller::class, 'index'])->name('tools.index');
Route::get('/tools/create', [Tool2Controller::class, 'create'])->name('tools.create');
Route::post('/tools', [Tool2Controller::class, 'store'])->name('tools.store');
Route::get('/tools/{id}', [Tool2Controller::class, 'show'])->name('tools.show');
Route::get('/tools/{id}/edit', [Tool2Controller::class, 'edit'])->name('tools.edit');
Route::put('/tools/{id}', [Tool2Controller::class, 'update'])->name('tools.update');
Route::delete('/tools/{id}', [Tool2Controller::class, 'destroy'])->name('tools.destroy');

// --------------Statistik -----------------
Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');
// ---------- // Statistik // --------------

// ---------- send Emails ------------------
Route::get('/emails/create', [Email2Controller::class, 'create'])->name('email.form');
Route::post('/emails', [Email2Controller::class, 'store'])->name('send.email');
// ---------- // send Emails // ------------

// ---------- Maintenance Mode ------------------
Route::get('/toggle-maintenance-mode', [MaintenanceModeController::class, 'index'])->name('maintenance.mode');
Route::post('/toggle-maintenance-mode', [MaintenanceModeController::class, 'toggleMaintenanceMode'])->name('maintenance.mode');
// ---------- //Maintenance Mode// --------------
});







Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

