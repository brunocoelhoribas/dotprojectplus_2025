<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\CompanyHumanResourceController;
use App\Http\Controllers\Company\CompanyOrganogramController;
use App\Http\Controllers\Company\CompanyRoleController;
use App\Http\Controllers\Execution\ExecutionController;
use App\Http\Controllers\HumanResource\HumanResourceSkillController;
use App\Http\Controllers\Initiating\InitiatingController;
use App\Http\Controllers\Initiating\InitiatingStakeholderController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Planning\Acquisition\AcquisitionController;
use App\Http\Controllers\Planning\Communication\CommunicationController;
use App\Http\Controllers\Planning\PlanningBudgetController;
use App\Http\Controllers\Planning\PlanningController;
use App\Http\Controllers\Planning\PlanningCostController;
use App\Http\Controllers\Planning\PlanningQualityController;
use App\Http\Controllers\Planning\Risk\RiskController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\User\UserCostController;
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

    Route::prefix('users/{user}')->name('users.')->group(function () {
        Route::post('costs', [UserCostController::class, 'store'])->name('costs.store');
        Route::put('costs/{cost}', [UserCostController::class, 'update'])->name('costs.update');
        Route::delete('costs/{cost}', [UserCostController::class, 'destroy'])->name('costs.destroy');
    });

    Route::resource('companies', CompanyController::class);

    Route::prefix('companies/{company}')->name('companies.')->group(function () {
        Route::resource('roles', CompanyRoleController::class)->except(['index', 'show']);
        Route::post('organogram', [CompanyOrganogramController::class, 'update'])->name('organogram.update');

        Route::post('hr', [CompanyHumanResourceController::class, 'store'])->name('hr.store');
        Route::delete('hr/{hr_id}', [CompanyHumanResourceController::class, 'destroy'])->name('hr.destroy');
        Route::get('hr/{hr_id}', [CompanyHumanResourceController::class, 'show'])->name('hr.show');
        Route::put('hr/{hr_id}', [CompanyHumanResourceController::class, 'update'])->name('hr.update');
        Route::post('/hr/{hr_id}/skills', [HumanResourceSkillController::class, 'store'])->name('hr.skills.store');
        Route::delete('/hr/{hr_id}/skills/{skill_id}', [HumanResourceSkillController::class, 'destroy'])->name('hr.skills.destroy');
    });

    Route::post('projects/batch-update', [ProjectController::class, 'batchUpdate'])
        ->name('projects.batchUpdate');

    Route::resource('projects', ProjectController::class);

    Route::prefix('projects/{project}')->name('projects.')->group(function () {
        Route::post('initiating', [InitiatingController::class, 'storeOrUpdate'])->name('initiating');

        Route::get('gantt-data', [PlanningController::class, 'ganttData'])->name('gantt.data');

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

        Route::post('/costs', [PlanningCostController::class, 'store'])->name('costs.store');
        Route::put('/costs/{cost}', [PlanningCostController::class, 'update'])->name('costs.update');
        Route::delete('/costs/{cost}', [PlanningCostController::class, 'destroy'])->name('costs.destroy');
        Route::get('/costs/budget', [PlanningBudgetController::class, 'edit'])->name('costs.budget.edit');
        Route::post('/costs/budget/update', [PlanningBudgetController::class, 'update'])->name('costs.budget.update');

        Route::post('/risks', [RiskController::class, 'store'])->name('risks.store');
        Route::put('/risks/{risk}', [RiskController::class, 'update'])->name('risks.update');
        Route::get('/risks/plan', [RiskController::class, 'editPlan'])->name('risks.plan.edit');
        Route::post('/risks/plan', [RiskController::class, 'updatePlan'])->name('risks.plan.update');
        Route::get('/risks/checklist', [RiskController::class, 'checklist'])->name('risks.checklist');
        Route::post('/risks/checklist/import', [RiskController::class, 'importChecklist'])->name('risks.checklist.import');
        Route::get('/risks/watchlist', [RiskController::class, 'watchList'])->name('risks.watchlist');
        Route::get('/risks/short-term', [RiskController::class, 'shortTermList'])->name('risks.short_term');
        Route::get('/risks/lessons-learned', [RiskController::class, 'lessonsLearnedList'])->name('risks.lessons_learned');
        Route::get('/risks/response_list', [RiskController::class, 'responseList'])->name('risks.response_list');

        Route::post('/quality/plan', [PlanningQualityController::class, 'updatePlan'])->name('quality.update_plan');
        Route::post('/quality/requirement', [PlanningQualityController::class, 'storeRequirement'])->name('quality.store_requirement');
        Route::delete('/quality/requirement/{requirement}', [PlanningQualityController::class, 'destroyRequirement'])->name('quality.destroy_requirement');
        Route::post('/quality/assurance', [PlanningQualityController::class, 'storeAssurance'])->name('quality.store_assurance');
        Route::delete('/quality/assurance/{item}', [PlanningQualityController::class, 'destroyAssurance'])->name('quality.destroy_assurance');
        Route::post('/quality/goal', [PlanningQualityController::class, 'storeGoal'])->name('quality.store_goal');
        Route::post('/quality/goal/{goal}/question', [PlanningQualityController::class, 'storeQuestion'])->name('quality.store_question');
        Route::post('/quality/question/{question}/metric', [PlanningQualityController::class, 'storeMetric'])->name('quality.store_metric');
        Route::delete('/quality/goal/{goal}', [PlanningQualityController::class, 'destroyGoal'])->name('quality.destroy_goal');
        Route::delete('/quality/question/{question}', [PlanningQualityController::class, 'destroyQuestion'])->name('quality.destroy_question');
        Route::delete('/quality/metric/{metric}', [PlanningQualityController::class, 'destroyMetric'])->name('quality.destroy_metric');

        Route::post('/communication/event', [CommunicationController::class, 'store'])->name('communication.store');
        Route::get('/communication/event/{communication}', [CommunicationController::class, 'show'])->name('show');
        Route::put('/communication/event/{communication}', [CommunicationController::class, 'update'])->name('update');
        Route::delete('/communication/event/{communication}', [CommunicationController::class, 'destroy'])->name('communication.destroy');
        Route::post('/communication/channel', [CommunicationController::class, 'storeChannel'])->name('communication.store_channel');
        Route::post('/channel/delete', [CommunicationController::class, 'destroyChannel'])->name('communication.destroy_channel');
        Route::post('/communication/frequency', [CommunicationController::class, 'storeFrequency'])->name('communication.store_frequency');
        Route::post('/frequency/delete', [CommunicationController::class, 'destroyFrequency'])->name('communication.destroy_frequency');

        Route::post('/acquisition', [AcquisitionController::class, 'store'])->name('acquisition.store');
        Route::get('/acquisition/{acquisition}', [AcquisitionController::class, 'show'])->name('acquisition.show');
        Route::put('/acquisition/{acquisition}', [AcquisitionController::class, 'update'])->name('acquisition.update');
        Route::delete('/acquisition/{acquisition}', [AcquisitionController::class, 'destroy'])->name('acquisition.destroy');

        Route::get('/plan/pdf', [PlanningController::class, 'projectPlanPdf'])->name('plan.pdf');

        Route::get('/execution', [ExecutionController::class, 'index'])->name('execution.index');
        Route::post('/execution/log', [ExecutionController::class, 'storeLog'])->name('execution.log.store');

        Route::get('/tasks/{task}/logs', [ExecutionController::class, 'getTaskLogs'])->name('tasks.logs.list');
        Route::delete('/tasks/logs/{log}', [ExecutionController::class, 'destroyLog'])->name('tasks.logs.destroy');

        Route::get('/closure', [App\Http\Controllers\Closure\ClosureController::class, 'show'])->name('closure.show');
        Route::post('/closure', [App\Http\Controllers\Closure\ClosureController::class, 'store'])->name('closure.store');
    });

    Route::get('projects/{project}/initiating/pdf', [InitiatingController::class, 'generatePDF'])->name('initiating.pdf');
    Route::get('initiating/{initiating}/stakeholders/pdf', [InitiatingStakeholderController::class, 'generatePDF'])->name('initiating.stakeholders.pdf');

    Route::post('stakeholders', [InitiatingStakeholderController::class, 'store'])->name('stakeholders.store');
    Route::put('stakeholders/{stakeholder}', [InitiatingStakeholderController::class, 'update'])->name('stakeholders.update');
    Route::delete('stakeholders/{stakeholder}', [InitiatingStakeholderController::class, 'destroy'])->name('stakeholders.destroy');

});
