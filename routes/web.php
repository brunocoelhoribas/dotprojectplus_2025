<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InitiatingController;
use App\Http\Controllers\InitiatingStakeholderController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectRiskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Autenticação)
|--------------------------------------------------------------------------
*/
Route::get('/', static function () {
    return redirect()->route('login');
});

Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', static function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('companies', CompanyController::class);

    Route::post('projects/batch-update', [ProjectController::class, 'batchUpdate'])
        ->name('projects.batchUpdate');

    Route::resource('projects', ProjectController::class);

    Route::prefix('projects/{project}')->name('projects.')->group(function () {
        Route::post('initiating', [InitiatingController::class, 'storeOrUpdate'])
            ->name('initiating');

        Route::post('wbs', [PlanningController::class, 'storeWbsItem'])->name('wbs.store');
        Route::delete('wbs/{wbsItem}', [PlanningController::class, 'destroyWbsItem'])->name('wbs.destroy');
        Route::post('wbs/{wbsItem}/move/{direction}', [PlanningController::class, 'moveWbsItem'])->name('wbs.move');

        Route::post('wbs/{wbsItem}/activity', [PlanningController::class, 'storeActivity'])->name('activity.store');

        Route::put('activities/{task}', [PlanningController::class, 'updateActivity'])->name('activity.update');
        Route::delete('activities/{task}', [PlanningController::class, 'destroyActivity'])->name('activity.destroy');
        Route::post('activities/{task}/move/{direction}', [PlanningController::class, 'moveActivity'])->name('activity.move');

        Route::get('sequencing', [PlanningController::class, 'sequencingIndex'])
            ->name('sequencing.index');
        Route::post('sequencing', [PlanningController::class, 'storeDependency'])
            ->name('sequencing.store');
        Route::delete('sequencing/{task}/{predecessor}', [PlanningController::class, 'destroyDependency'])
            ->name('sequencing.destroy');

        Route::post('training', [PlanningController::class, 'storeTraining'])->name('training.store');
        Route::post('minutes', [PlanningController::class, 'storeMinute'])->name('minutes.store');
        Route::put('minutes/{minute}', [PlanningController::class, 'updateMinute'])->name('minutes.update');
        Route::delete('minutes/{minute}', [PlanningController::class, 'destroyMinute'])->name('minutes.destroy');
        Route::post('dependencies', [PlanningController::class, 'storeDependency'])->name('dependencies.store');

        Route::get('planning/tab/{tab}', [PlanningController::class, 'loadTabContent'])
            ->name('planning.tab');

        Route::post('/risks', [ProjectRiskController::class, 'store'])->name('risks.store');
        Route::put('/risks/{risk}', [ProjectRiskController::class, 'update'])->name('risks.update');
        Route::get('/risks/plan', [ProjectRiskController::class, 'editPlan'])->name('risks.plan.edit');
        Route::post('/risks/plan', [ProjectRiskController::class, 'updatePlan'])->name('risks.plan.update');
    });

    Route::get('projects/{project}/gantt-data', [PlanningController::class, 'ganttData'])
        ->name('projects.gantt.data');
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
});
