<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InitiatingController;
use App\Http\Controllers\InitiatingStakeholderController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return redirect()->route('login');
});

Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

Route::resource('companies', CompanyController::class)->middleware('auth');
Route::resource('projects', ProjectController::class);

Route::post('projects/batch-update', [ProjectController::class, 'batchUpdate'])
    ->name('projects.batchUpdate');
Route::post('projects/{project}/initiating', [InitiatingController::class, 'storeOrUpdate'])
    ->name('initiating.storeOrUpdate');
Route::get('projects/{project}/initiating/pdf', [InitiatingController::class, 'generatePDF'])
    ->name('initiating.pdf');

Route::post('stakeholders', [InitiatingStakeholderController::class, 'store'])
    ->name('stakeholders.store');
Route::put('stakeholders/{stakeholder}', [InitiatingStakeholderController::class, 'update'])
    ->name('stakeholders.update');
Route::delete('stakeholders/{stakeholder}', [InitiatingStakeholderController::class, 'destroy'])
    ->name('stakeholders.destroy');
Route::get('initiating/{initiating}/stakeholders/pdf', [InitiatingStakeholderController::class, 'generatePDF'])
    ->name('initiating.stakeholders.pdf');

Route::get('dashboard', static function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');
