<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Management\UserController;


Route::get('/', function () {
    return view('welcome');
});


Route::prefix('v1/users')->name('user.')->controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('{id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
    Route::put('{id}', 'update')->name('update');
    Route::delete('{id}', 'destroy')->name('destroy');
});

// Route::get('v1/users', [UserController::class, 'index'])->name('user.index'); //v1
// Route::get('v1/users/{id}', [UserController::class, 'show'])->name('user.show'); //v1
// Route::post('v1/users', [UserController::class, 'create'])->name('user.create'); //v1
// Route::put('v1/users/{id}', [UserController::class, 'update'])->name('user.update');//v1
// Route::delete('v1/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');//v1
