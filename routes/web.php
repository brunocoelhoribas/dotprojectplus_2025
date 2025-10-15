<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');
