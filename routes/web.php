<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [UserController::class , 'index'])->name('users');
    Route::get('/users/create', [UserController::class , 'create'])->name('create-user');
    Route::post('/users/store', [UserController::class , 'store'])->name('store-user');
    Route::get('/users/edit/{id}', [UserController::class , 'edit'])->name('edit-user');
    Route::post('/users/update/{id}', [UserController::class , 'update'])->name('update-user');
    Route::post('/users/delete', [UserController::class , 'destroy'])->name('delete-user');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::get('/roles/create', [RoleController::class , 'create'])->name('create-role');
    Route::post('/roles/store', [RoleController::class , 'store'])->name('store-role');
    Route::get('/roles/edit/{id}', [RoleController::class , 'edit'])->name('edit-role');
    Route::post('/roles/update/{id}', [RoleController::class , 'update'])->name('update-role');
    Route::post('/roles/delete', [RoleController::class , 'destroy'])->name('delete-role');

    Route::get('/roles/permissions/{roleId}', [PermissionController::class, 'list_permissions'])->name('list-permissions');
});

require __DIR__.'/auth.php';
