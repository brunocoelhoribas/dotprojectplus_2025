<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Models\Initiating\Initiating;
use App\Models\Initiating\InitiatingStakeholder;
use App\Models\Monitoring\MonitoringBaseline;
use App\Models\Monitoring\MonitoringBaselineTask;
use App\Models\Planning\Acquisition\AcquisitionPlanning;
use App\Models\Planning\Communication\Communication;
use App\Models\Planning\Communication\CommunicationChannel;
use App\Models\Planning\Communication\CommunicationFrequency;
use App\Models\Planning\Quality\QualityPlanning;
use App\Models\Planning\Risk\Risk;
use App\Models\Project\Project;
use App\Models\Project\ProjectMinute;
use App\Models\Project\ProjectTraining;
use App\Models\Project\ProjectWbsItem;
use App\Models\Project\Task\Task;
use App\Models\Project\Task\TasksWorkpackage;
use App\Models\User\User;
use App\Models\User\UserContact;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class PlanningController extends Controller {

//    public function index(Request $request, Project $project): View {
//
//        $wbsItems = ProjectWbsItem::with([
//            'tasks.owner.contact',
//            'tasks.estimation'
//        ])
//            ->where('project_id', $project->project_id)
//            ->orderBy('sort_order')
//            ->get();
//
//        return view('projects.planning.index', [
//            'project' => $project,
//            'wbsItems' => $wbsItems,
//        ]);
//    }

    /**
     * @throws Throwable
     */
    public function storeWbsItem(Request $request, Project $project): RedirectResponse {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|integer|exists:project_eap_items,id',
        ]);

        $parentId = $request->input('parent_id');

        DB::transaction(static function () use ($project, $request, $parentId) {
            $parentItem = ProjectWbsItem::findOrFail($parentId);

            $newIndentation = $parentItem->identation . '&nbsp;&nbsp;&nbsp;';

            $childCount = ProjectWbsItem::where('project_id', $project->project_id)
                ->where('identation', $newIndentation)
                ->where('sort_order', '>', $parentItem->sort_order)
                ->count();

            $newNumber = $parentItem->number . '.' . ($childCount + 1);

            $insertPosition = $parentItem->sort_order + 1;

            ProjectWbsItem::where('project_id', $project->project_id)
                ->where('sort_order', '>=', $insertPosition)
                ->increment('sort_order');

            ProjectWbsItem::create([
                'project_id' => $project->project_id,
                'name' => $request->input('name'),
                'number' => $newNumber,
                'sort_order' => $insertPosition,
                'is_leaf' => 1, // Nasce como folha
                'indentation' => $newIndentation,
            ]);

            $parentItem->update(['is_leaf' => 0]);
        });

        return redirect()->route('projects.planning.tab', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'activities'
        ])->with('success', __('planning/messages.wbs.created'));
    }

    /**
     * @throws Throwable
     */
    public function storeActivity(Request $request, Project $project, $wbsItemId): RedirectResponse {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'task_start_date' => 'nullable|date',
            'task_end_date' => 'nullable|date',
            'task_duration' => 'nullable|numeric',
        ]);

        DB::transaction(static function () use ($request, $project, $wbsItemId) {

            $task = Task::create([
                'task_name' => $request->input('task_name'),
                'task_project' => $project->project_id,
                'task_owner' => auth()->id(),
                'task_start_date' => $request->input('task_start_date'),
                'task_end_date' => $request->input('task_end_date'),
                'task_duration' => $request->input('task_duration') ?? 0,
                'task_duration_type' => 24,
                'task_status' => 0,
                'task_priority' => 0,
                'task_percent_complete' => 0,
            ]);

            TasksWorkpackage::create([
                'task_id' => $task->task_id,
                'eap_item_id' => $wbsItemId
            ]);
        });

        return redirect()->route('projects.planning.tab', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'activities'
        ])->with('success', __('planning/messages.activity.created'));
    }

    public function updateActivity(Request $request, Project $project, Task $task): RedirectResponse {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'task_start_date' => 'nullable|date',
            'task_end_date' => 'nullable|date',
            'task_duration' => 'nullable|numeric',
            'task_percent_complete' => 'nullable|integer|min:0|max:100',
        ]);

        $task->update($validated);

        return redirect()->route('projects.planning.tab', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'activities'
        ])->with('success', __('planning/messages.activity.updated'));
    }

    /**
     * Reordena um item da EAP (e seus filhos) para cima ou para baixo.
     * @throws Throwable
     */
    public function moveWbsItem(Project $project, ProjectWbsItem $wbsItem, string $direction): RedirectResponse {
        if (!in_array($direction, ['up', 'down'])) {
            return redirect()->back();
        }

        DB::transaction(static function () use ($project, $wbsItem, $direction) {
            $allItems = ProjectWbsItem::where('project_id', $project->project_id)
                ->orderBy('sort_order', 'asc')
                ->get();

            $currentIndex = $allItems->search(fn($i) => $i->id === $wbsItem->id);
            $currentLevel = $wbsItem->level;

            $blockSize = 1;
            for ($i = $currentIndex + 1; $i < $allItems->count(); $i++) {
                if ($allItems[$i]->level > $currentLevel) {
                    $blockSize++;
                } else {
                    break;
                }
            }

            $targetIndex = null;
            $targetSize = 0;

            if ($direction === 'down') {
                $candidateIndex = $currentIndex + $blockSize;
                if ($candidateIndex < $allItems->count()) {
                    $targetItem = $allItems[$candidateIndex];

                    if ($targetItem->level === $currentLevel) {
                        $targetIndex = $candidateIndex;
                        $targetSize = 1;

                        for ($i = $targetIndex + 1; $i < $allItems->count(); $i++) {
                            if ($allItems[$i]->level > $currentLevel) {
                                $targetSize++;
                            } else {
                                break;
                            }
                        }
                    }
                }
            } else {
                for ($i = $currentIndex - 1; $i >= 0; $i--) {
                    if ($allItems[$i]->level === $currentLevel) {
                        $targetIndex = $i;
                        $targetSize = 1;
                        for ($j = $targetIndex + 1; $j < $currentIndex; $j++) {
                            if ($allItems[$j]->level > $currentLevel) {
                                $targetSize++;
                            } else {
                                break;
                            }
                        }
                        if (($targetIndex + $targetSize) === $currentIndex) {
                            break;
                        }

                        $targetIndex = null;
                    } elseif ($allItems[$i]->level < $currentLevel) {
                        break;
                    }
                }
            }

            if ($targetIndex !== null) {
                $currentBlockIds = $allItems->slice($currentIndex, $blockSize)->pluck('id');
                $targetBlockIds = $allItems->slice($targetIndex, $targetSize)->pluck('id');
                $minSortOrder = min($allItems[$currentIndex]->sort_order, $allItems[$targetIndex]->sort_order);


                if ($direction === 'down') {
                    $firstBlockIds = $targetBlockIds;
                    $secondBlockIds = $currentBlockIds;
                } else {
                    $firstBlockIds = $currentBlockIds;
                    $secondBlockIds = $targetBlockIds;
                }

                $counter = $minSortOrder;

                foreach ($firstBlockIds as $id) {
                    ProjectWbsItem::where('id', $id)->update(['sort_order' => $counter++]);
                }
                foreach ($secondBlockIds as $id) {
                    ProjectWbsItem::where('id', $id)->update(['sort_order' => $counter++]);
                }
            }
        });

        return redirect()->route('projects.planning.tab', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'activities'
        ]);
    }


    public function moveActivity(Project $project, Task $task, string $direction): RedirectResponse {
        if (!in_array($direction, ['up', 'down'])) {
            return redirect()->back();
        }

        $link = TasksWorkpackage::where('task_id', $task->task_id)->first();
        if (!$link) {
            return redirect()->back();
        }

        $wbsId = $link->eap_item_id;

        $siblings = Task::join('dotp_tasks_workpackages as pivot', 'dotp_tasks.task_id', '=', 'pivot.task_id')
            ->where('pivot.eap_item_id', $wbsId)
            ->orderBy('task_order')
            ->orderBy('task_start_date')
            ->select('dotp_tasks.*')
            ->get();

        $currentIndex = $siblings->search(fn($t) => $t->task_id === $task->task_id);
        $targetIndex = ($direction === 'up') ? $currentIndex - 1 : $currentIndex + 1;

        if (isset($siblings[$targetIndex])) {
            $targetTask = $siblings[$targetIndex];
            $order1 = $task->task_order ?: $currentIndex;
            $order2 = $targetTask->task_order ?: $targetIndex;

            if ($order1 === $order2) {
                $order1 = $currentIndex;
                $order2 = $targetIndex;
            }

            $task->update(['task_order' => $order2]);
            $targetTask->update(['task_order' => $order1]);
        }

        return redirect()->route('projects.planning.tab', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'activities'
        ]);
    }

    /**
     * @throws Throwable
     */
    public function destroyWbsItem(Project $project, ProjectWbsItem $wbsItem): RedirectResponse {
        DB::transaction(static function () use ($project, $wbsItem) {
            $allBelow = ProjectWbsItem::where('project_id', $project->project_id)
                ->where('sort_order', '>', $wbsItem->sort_order)
                ->orderBy('sort_order')
                ->get();

            $idsToDelete = [$wbsItem->id];
            $baseLevel = $wbsItem->level;

            foreach ($allBelow as $candidate) {
                if ($candidate->level > $baseLevel) {
                    $idsToDelete[] = $candidate->id;
                } else {
                    break;
                }
            }

            TasksWorkpackage::whereIn('eap_item_id', $idsToDelete)->delete();
            ProjectWbsItem::whereIn('id', $idsToDelete)->delete();
        });

        return redirect()->route('projects.planning.tab', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'activities'
        ])->with('success', __('planning/messages.wbs.deleted'));
    }

    /**
     * @throws Throwable
     */
    public function destroyActivity(Project $project, Task $task): RedirectResponse {
        DB::transaction(static function () use ($task) {
            TasksWorkpackage::where('task_id', $task->task_id)->delete();
            $task->delete();
        });

        return redirect()->route('projects.planning.tab', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'activities'
        ])->with('success', __('planning/messages.activity.deleted'));
    }

    public function sequencingIndex(Project $project): View {
        $tasks = Task::with('predecessors')
            ->where('task_project', $project->project_id)
            ->orderBy('task_id')
            ->get();

        return view('projects.planning.partials.sequencing', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }

    public function storeDependency(Request $request, Project $project): RedirectResponse {
        $request->validate([
            'task_id' => 'required|integer',
            'predecessor_id' => 'required|integer|different:task_id',
        ]);

        $exists = DB::table('dotp_task_dependencies')
            ->where('dependencies_task_id', $request->task_id)
            ->where('dependencies_req_task_id', $request->predecessor_id)
            ->exists();

        if (!$exists) {
            DB::table('dotp_task_dependencies')->insert([
                'dependencies_task_id' => $request->task_id,
                'dependencies_req_task_id' => $request->predecessor_id
            ]);
        }

        // TODO: Aqui você poderia chamar uma função para recalcular as datas do projeto (Critical Path)

        return redirect()->route('projects.sequencing.index', $project)
            ->with('success', __('planning/messages.dependency.added'));
    }

    public function destroyDependency(Project $project, $taskId, $predecessorId): RedirectResponse {
        DB::table('dotp_task_dependencies')
            ->where('dependencies_task_id', $taskId)
            ->where('dependencies_req_task_id', $predecessorId)
            ->delete();

        return redirect()->route('projects.sequencing.index', $project)
            ->with('success', __('planning/messages.dependency.removed'));
    }

    public function storeTraining(Request $request, Project $project): RedirectResponse {
        $request->validate([
            'description' => 'nullable|string',
        ]);

        ProjectTraining::updateOrCreate(
            [
                'project_id' => $project->project_id,
                'description' => $request->input('description')
            ],
        );

        return redirect()->route('projects.show', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'activities'
        ])->with('success', __('planning/messages.training.saved'));
    }

    public function storeMinute(Request $request, Project $project): RedirectResponse {
        $minute = ProjectMinute::create([
            'project_id' => $project->project_id,
            'minute_date' => $request->input('date'),
            'description' => $request->input('description'),
            'isEffort' => $request->has('is_effort') ? 1 : 0,
            'isDuration' => $request->has('is_duration') ? 1 : 0,
            'isResource' => $request->has('is_resource') ? 1 : 0,
            'isSize' => $request->has('is_size') ? 1 : 0,
        ]);

        if ($request->has('member_ids')) {
            $minute->members()->sync($request->input('member_ids'));
        }

        return redirect()->route('projects.show', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'activities'
        ])->with('success', __('planning/messages.minutes.created'));
    }

    public function updateMinute(Request $request, Project $project, ProjectMinute $minute): RedirectResponse {
        $minute->update([
            'minute_date' => $request->input('date'),
            'description' => $request->input('description'),
            'isEffort' => $request->has('is_effort') ? 1 : 0,
            'isDuration' => $request->has('is_duration') ? 1 : 0,
            'isResource' => $request->has('is_resource') ? 1 : 0,
            'isSize' => $request->has('is_size') ? 1 : 0,
        ]);

        if ($request->has('member_ids')) {
            $minute->members()->sync($request->input('member_ids'));
        } else {
            $minute->members()->detach();
        }

        return redirect()->route('projects.show', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'activities'
        ])->with('success', __('planning/messages.minutes.updated'));
    }

    public function destroyMinute(Project $project, ProjectMinute $minute): RedirectResponse {
        $minute->members()->detach();
        $minute->delete();

        return redirect()->route('projects.show', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'activities'
        ])->with('success', __('planning/messages.minutes.deleted'));
    }

    public static function numberToAlpha($n): string {
        $r = '';
        for ($i = 1; $n >= 0 && $i < 10; $i++) {
            $r = chr(97 + ($n % 26)) . $r;
            $n = (int)($n / 26) - 1;
        }
        return $r;
    }

    public function ganttData(Project $project): JsonResponse {
        $tasks = Task::with('predecessors')
            ->where('task_project', $project->project_id)
            ->whereNotNull('task_start_date')
            ->whereNotNull('task_end_date')
            ->orderBy('task_start_date')
            ->get();

        $ganttTasks = $tasks->map(function ($task) {
            $dependencies = $task->predecessors->pluck('task_id')->implode(', ');

            return [
                'id' => (string)$task->task_id,
                'name' => $task->task_name,
                'start' => $task->task_start_date->format('Y-m-d'),
                'end' => $task->task_end_date->format('Y-m-d'),
                'progress' => (int)$task->task_percent_complete,
                'dependencies' => $dependencies,
                'custom_class' => $task->task_percent_complete === 100 ? 'bar-completed' : 'bar-running'
            ];
        });

        return response()->json($ganttTasks);
    }

    /**
     * @throws Throwable
     */
    public function loadTabContent(Request $request, Project $project, string $tab): JsonResponse {
        return match ($tab) {
            'activities' => $this->handleActivitiesTab($project),
            'schedule' => $this->handleScheduleTab($request, $project),
            'costs' => $this->renderSimpleTab('costs', $project),
            'risks' => $this->handleRisksTab($project),
            'quality' => $this->handleQualityTab($project),
            'communication' => $this->handleCommunicationTab($project),
            'acquisition' => $this->handleAcquisitionTab($project),
            'stakeholders' => $this->handleStakeholderTab($project),
            'plan' => $this->renderSimpleTab('plan', $project),
            default => $this->renderUnderConstruction(),
        };
    }

    /**
     * @throws Throwable
     */
    private function handleActivitiesTab(Project $project): JsonResponse {
        $wbsItems = ProjectWbsItem::with(['tasks.owner.contact', 'tasks.estimation'])
            ->where('project_id', $project->project_id)
            ->orderBy('sort_order')
            ->get();

        $html = view('projects.planning.tabs.activities', [
            'project' => $project,
            'wbsItems' => $wbsItems
        ])->render();

        $actions = '
            <div class="btn-group">
                <a href="' . route('projects.sequencing.index', $project) . '" class="btn btn-outline-secondary btn-sm">' . __('planning/view.activities.sequencing') . '</a>
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#trainingModal">' . __('planning/view.activities.training') . '</button>
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#minutesModal">' . __('planning/view.activities.minutes') . '</button>
            </div>';

        return response()->json(['html' => $html, 'actions' => $actions]);
    }

    /**
     * @throws Throwable
     */
    private function handleScheduleTab(Request $request, Project $project): JsonResponse {
        $baselines = MonitoringBaseline::where('project_id', $project->project_id)
            ->orderBy('baseline_date', 'desc')
            ->get();

        $selectedBaselineId = $request->query('baseline_id', 'current');
        $reportDateStr = $request->query('report_date', date('Y-m-d'));

        $ganttTasks = $this->fetchGanttTasks($project, $selectedBaselineId);
        $evmData = $this->calculateEvmMetrics($ganttTasks, $reportDateStr);

        $html = view('projects.planning.tabs.schedule', [
            'project' => $project,
            'ganttData' => $ganttTasks,
            'evmData' => $evmData,
            'baselines' => $baselines,
            'selectedBaseline' => $selectedBaselineId
        ])->render();

        return response()->json(['html' => $html, 'actions' => '']);
    }

    /**
     * @throws Throwable
     */
    private function handleRisksTab(Project $project): JsonResponse {
        $risks = Risk::where('risk_project', '<>', null)
            ->orderBy('risk_id', 'asc')
            ->get();

        $activeRisks = $risks->filter(fn($r) => (int)$r->risk_active === 1);
        $inactiveRisks = $risks->filter(fn($r) => (int)$r->risk_active === 0);

        $users = User::join('dotp_contacts', 'dotp_users.user_contact', '=', 'dotp_contacts.contact_id')
            ->orderBy('contact_first_name')
            ->select('user_id', DB::raw("CONCAT(contact_first_name, ' ', contact_last_name) as full_name"))
            ->pluck('full_name', 'user_id');

        $tasks = Task::where('task_project', $project->project_id)
            ->orderBy('task_start_date')
            ->pluck('task_name', 'task_id');

        $html = view('projects.planning.tabs.risks.risks', [
            'project' => $project,
            'activeRisks' => $activeRisks,
            'inactiveRisks' => $inactiveRisks,
            'users' => $users,
            'tasks' => $tasks
        ])->render();

        return response()->json(['html' => $html, 'actions' => '']);
    }

    /**
     * @throws Throwable
     */
    private function handleQualityTab(Project $project): JsonResponse {
        $qualityPlan = QualityPlanning::with([
            'requirements',
            'assuranceItems',
            'goals.questions.metrics'
        ])->firstOrCreate(['project_id' => $project->project_id]);

        $users = User::join('dotp_contacts', 'dotp_users.user_contact', '=', 'dotp_contacts.contact_id')
            ->orderBy('contact_first_name')
            ->select('user_id', DB::raw("CONCAT(contact_first_name, ' ', contact_last_name) as full_name"))
            ->pluck('full_name', 'user_id');

        $html = view('projects.planning.tabs.quality', [
            'project' => $project,
            'plan' => $qualityPlan,
            'users' => $users
        ])->render();

        return response()->json(['html' => $html, 'actions' => '']);
    }

    /**
     * @throws Throwable
     */
    private function handleCommunicationTab(Project $project): JsonResponse {
        $communications = Communication::with(['channel', 'frequency'])
            ->where('communication_project_id', $project->project_id)
            ->orderBy('communication_title')
            ->get();

        $channels = CommunicationChannel::orderBy('communication_channel')->get();
        $frequencies = CommunicationFrequency::orderBy('communication_frequency')->get();

        $users = User::join('dotp_contacts', 'dotp_users.user_contact', '=', 'dotp_contacts.contact_id')
            ->orderBy('contact_first_name')
            ->select('user_id', DB::raw("CONCAT(contact_first_name, ' ', contact_last_name) as full_name"))
            ->pluck('full_name', 'user_id');

        $html = view('projects.planning.tabs.communication', [
            'project' => $project,
            'communications' => $communications,
            'channels' => $channels,
            'frequencies' => $frequencies,
            'users' => $users
        ])->render();

        return response()->json(['html' => $html, 'actions' => '']);
    }

    /**
     * @throws Throwable
     */
    private function handleAcquisitionTab(Project $project): JsonResponse {
        $acquisitions = AcquisitionPlanning::with(['criteria', 'requirements', 'roles'])
            ->where('project_id', $project->project_id)
            ->get();

        $html = view('projects.planning.tabs.acquisition', [
            'project' => $project,
            'acquisitions' => $acquisitions
        ])->render();

        return response()->json(['html' => $html, 'actions' => '']);
    }

    /**
     * @throws Throwable
     */
    private function handleStakeholderTab(Project $project): JsonResponse {
        $initiating = Initiating::where('project_id', $project->project_id)->first();

        $stakeholders = collect([]);
        if ($initiating) {
            $stakeholders = InitiatingStakeholder::with('contact')
                ->where('initiating_id', $initiating->initiating_id)
                ->get();
        }

        $contacts = UserContact::orderBy('contact_first_name')
            ->select('contact_id', DB::raw("CONCAT(contact_first_name, ' ', contact_last_name) as full_name"))
            ->get();

        $html = view('projects.planning.tabs.stakeholder', [
            'project' => $project,
            'initiating' => $initiating,
            'stakeholders' => $stakeholders,
            'contacts' => $contacts
        ])->render();

        return response()->json(['html' => $html, 'actions' => '']);
    }

    /**
     * @throws Throwable
     */
    private function renderSimpleTab(string $viewName, Project $project): JsonResponse {
        $html = view("projects.planning.tabs.$viewName", ['project' => $project])->render();
        return response()->json(['html' => $html, 'actions' => '']);
    }

    private function renderUnderConstruction(): JsonResponse {
        $html = '<div class="text-center py-5 text-muted">Module under construction.</div>';
        return response()->json(['html' => $html, 'actions' => '']);
    }

    private function fetchGanttTasks(Project $project, string $baselineId): Collection {
        $tasksCollection = collect();

        if ($baselineId === 'current') {
            $wbsItems = ProjectWbsItem::with(['tasks' => fn($q) => $q->orderBy('task_start_date')])
                ->where('project_id', $project->project_id)
                ->orderBy('sort_order')
                ->get();

            foreach ($wbsItems as $wbs) {
                foreach ($wbs->tasks as $index => $task) {
                    $letter = self::numberToAlpha($index);
                    $fullId = "A.$wbs->number.$letter.";

                    $tasksCollection->push([
                        'id' => (string)$task->task_id,
                        'name' => "$fullId $task->task_name",
                        'start' => $this->formatDate($task->task_start_date),
                        'end' => $this->formatDate($task->task_end_date),
                        'progress' => $task->task_percent_complete ?? 0,
                        'budget' => $task->task_target_budget ?? 0
                    ]);
                }
            }
        } else {
            $baselineTasks = MonitoringBaselineTask::with(['originalTask.wbsItem'])
                ->where('baseline_id', $baselineId)
                ->get();

            $grouped = $baselineTasks->groupBy(fn($item) => $item->originalTask->task_wbs_entity ?? 0)
                ->sortBy(fn($tasks) => $tasks->first()->originalTask->wbsItem->sort_order ?? 999);

            foreach ($grouped as $tasks) {
                $sortedTasks = $tasks->sortBy('task_start_date');
                $wbsNumber = $tasks->first()->originalTask->wbsItem->number ?? '?';
                $index = 0;

                foreach ($sortedTasks as $bt) {
                    $letter = self::numberToAlpha($index++);
                    $fullId = "A.$wbsNumber.$letter.";
                    $taskName = $bt->originalTask->task_name ?? 'Removed Task';

                    $tasksCollection->push([
                        'id' => (string)$bt->task_id,
                        'name' => "$fullId $taskName",
                        'start' => $this->formatDate($bt->task_start_date),
                        'end' => $this->formatDate($bt->task_end_date),
                        'progress' => $bt->task_percent_complete ?? 0,
                        'budget' => $bt->originalTask->task_target_budget ?? 0
                    ]);
                }
            }
        }

        return $tasksCollection;
    }

    private function calculateEvmMetrics(Collection $ganttTasks, string $reportDateStr): array {
        $tasksData = $ganttTasks->map(fn($t) => (object)[
            'start' => Carbon::parse($t['start']),
            'end' => Carbon::parse($t['end']),
            'budget' => $t['budget'],
            'percent' => $t['progress']
        ]);

        if ($tasksData->isEmpty()) {
            return $this->getEmptyEvmData();
        }

        $startDate = Carbon::parse($tasksData->min('start'))->startOfWeek();
        $endDate = Carbon::parse($tasksData->max('end'))->endOfWeek();

        $reportDate = Carbon::parse($reportDateStr)->endOfDay();
        $period = $startDate->copy();

        $labels = [];
        $plannedValueData = [];
        $earnedValueData = [];

        while ($period <= $endDate) {
            $labels[] = $period->format('d/m');

            $pvInPeriod = $tasksData->where('end', '<=', $period)->sum('budget');
            $plannedValueData[] = $pvInPeriod;

            if ($period <= $reportDate) {
                $evInPeriod = $this->calculateCumulativeEarnedValue($tasksData, $period);
                $earnedValueData[] = round($evInPeriod, 2);
            } else {
                $earnedValueData[] = null;
            }

            $period->addWeek();
        }

        $totalPV = $tasksData->where('end', '<=', $reportDate)->sum('budget');
        $totalEV = $this->calculateCumulativeEarnedValue($tasksData, $reportDate);

        $scheduleVariance = $totalEV - $totalPV;
        $spi = ($totalPV > 0) ? ($totalEV / $totalPV) : 0;

        return [
            'labels' => $labels,
            'vp' => $plannedValueData,
            'va' => $earnedValueData,
            'total_vp' => number_format($totalPV, 2, ',', '.'),
            'total_va' => number_format($totalEV, 2, ',', '.'),
            'vpr' => number_format($scheduleVariance, 2, ',', '.'),
            'idp' => number_format($spi, 2)
        ];
    }

    private function calculateCumulativeEarnedValue(Collection $tasks, Carbon $date): float {
        $totalEV = 0;

        foreach ($tasks as $task) {
            if (($task->start <= $date) && ($date >= $task->end)) {
                $totalEV += $task->budget * ($task->percent / 100);
            } else {
                $totalDuration = $task->start->diffInDays($task->end) ?: 1;
                $daysPassed = $task->start->diffInDays($date);

                $theoreticalProgress = min(1, $daysPassed / $totalDuration);
                $actualProgressRatio = $task->percent / 100;

                $totalEV += $task->budget * min($theoreticalProgress, $actualProgressRatio);
            }
        }

        return $totalEV;
    }

    private function formatDate($date): string {
        return $date ? $date->format('Y-m-d') : date('Y-m-d');
    }

    private function getEmptyEvmData(): array {
        return [
            'labels' => [],
            'vp' => [],
            'va' => [],
            'total_vp' => '0,00',
            'total_va' => '0,00',
            'vpr' => '0,00',
            'idp' => '0.00'
        ];
    }

}
