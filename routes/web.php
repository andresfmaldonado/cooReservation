<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ElementController;
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
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class , 'index'])->name('users')->middleware('permissions:6');
        Route::get('/create', [UserController::class , 'create'])->name('create-user')->middleware('permissions:3');
        Route::post('/store', [UserController::class , 'store'])->name('store-user')->middleware('permissions:3');
        Route::get('/edit/{id}', [UserController::class , 'edit'])->name('edit-user')->middleware('permissions:4');
        Route::post('/update/{id}', [UserController::class , 'update'])->name('update-user')->middleware('permissions:4');
        Route::post('/delete', [UserController::class , 'destroy'])->name('delete-user')->middleware('permissions:5');
    });

    Route::prefix('roles')->group(function() {
        Route::get('/', [RoleController::class, 'index'])->name('roles')->middleware('permissions:10');
        Route::get('/create', [RoleController::class , 'create'])->name('create-role')->middleware('permissions:7');
        Route::post('/store', [RoleController::class , 'store'])->name('store-role')->middleware('permissions:7');
        Route::get('/edit/{id}', [RoleController::class , 'edit'])->name('edit-role')->middleware('permissions:8');
        Route::post('/update/{id}', [RoleController::class , 'update'])->name('update-role') ->middleware('permissions:8');
        Route::post('/delete', [RoleController::class , 'destroy'])->name('delete-role')->middleware('permissions:9');

        Route::get('/permissions/{id}', [PermissionController::class, 'index'])->name('list-permissions') ->middleware('permissions:10');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('save-permissions') ->middleware('permissions:11');
    });

    Route::prefix('rooms')->group(function() {
        Route::get('/', [RoomController::class, 'index'])->name('rooms')->middleware('permissions:10');
        Route::get('/create', [RoomController::class , 'create'])->name('create-room')->middleware('permissions:7');
        Route::post('/store', [RoomController::class , 'store'])->name('store-room')->middleware('permissions:7');
        Route::get('/edit/{id}', [RoomController::class , 'edit'])->name('edit-room')->middleware('permissions:8');
        Route::post('/update/{id}', [RoomController::class , 'update'])->name('update-room')
        ->middleware('permissions:8');
        Route::post('/delete', [RoomController::class , 'destroy'])->name('delete-room')->middleware('permissions:9');
        Route::get('elements/{id}', [ElementController::class, 'index'])->name('list-elements')->middleware('permissions:10');
    });

    Route::prefix('elements')->group(function(){
        Route::get('/create', [ElementController::class, 'create'])->name('create-element')->middleware('permissions:10');
        Route::post('/store', [ElementController::class , 'store'])->name('store-element')->middleware('permissions:7');
        Route::get('/edit/{id}', [ElementController::class , 'edit'])->name('edit-element')->middleware('permissions:8');
        Route::post('/update/{id}', [ElementController::class , 'update'])->name('update-element')->middleware('permissions:8');
        Route::post('/roomElements/save', [ElementController::class, 'roomStore'])->name('save-room-elements') ->middleware('permissions:11');
    });
});

require __DIR__.'/auth.php';
