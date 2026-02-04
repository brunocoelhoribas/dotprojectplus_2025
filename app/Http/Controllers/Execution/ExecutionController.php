<?php

namespace App\Http\Controllers\Execution;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use App\Models\Project\ProjectWbsItem;
use App\Models\Project\Task\Task;
use App\Models\Project\Task\TaskLog;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ExecutionController extends Controller {
    /**
     * @throws Throwable
     */
    public function index(Request $request, Project $project) {
        $projectUsers = User::whereHas('contact', function ($query) use ($project) {
            $query->where('contact_company', $project->project_company);
        })->with('contact')->get();

        $userId = $request->get('user_id');
        $showCompleted = $request->get('show_completed', false);

        $wbsItems = ProjectWbsItem::where('project_id', $project->project_id)
            ->with(['tasks' => function ($query) use ($userId, $showCompleted) {

                if ($userId) {
                    $query->where(function ($q) use ($userId) {
                        $q->whereHas('resources', function ($r) use ($userId) {
                            $r->where('user_id', $userId);
                        })->orWhere('task_owner', $userId);
                    });
                }

                if (!$showCompleted) {
                    $query->where('task_percent_complete', '<', 100);
                }

                $query->with([
                    'owner.contact',
                    'resources.contact',
                    'logs' => function ($q) {
                        $q->orderBy('task_log_date', 'asc');
                    },
                    'estimatedRoles.allocations.humanResource.user.contact'
                ]);
            }])
            ->orderBy('sort_order')
            ->get();

        if ($request->ajax()) {
            return view('projects.partials.execution_table', compact('project', 'wbsItems'))->render();
        }

        return view('projects.tabs.execution', compact('project', 'wbsItems', 'projectUsers'));
    }

    public function storeLog(Request $request): JsonResponse {
        $request->validate([
            'task_id' => 'required|exists:dotp_tasks,task_id',
            'log_date' => 'required|date',
            'hours' => 'required|numeric|min:0.1',
            'description' => 'nullable|string'
        ]);

        TaskLog::create([
            'task_log_task' => $request->task_id,
            'task_log_name' => 'Log de Execução',
            'task_log_description' => $request->description ?? '',
            'task_log_creator' => auth()->id(),
            'task_log_hours' => $request->hours,
            'task_log_date' => $request->log_date,
            'task_log_costcode' => 0
        ]);

        $task = Task::find($request->task_id);

        $isConcluded = $request->boolean('concluded');

        if ($isConcluded) {
            $task->task_percent_complete = 100;
        } else if ($task->task_percent_complete === 0) {
            $task->task_percent_complete = 50;
        }

        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Apontamento salvo com sucesso!'
        ]);
    }

    public function getTaskLogs(Project $project, Task $task): string {
        $logs = $task->logs()->with('creator')->orderBy('task_log_date', 'desc')->get();

        return view('projects.partials.logs_list', compact('logs'))->render();
    }

    /**
     * Deleta um log
     */
    public function destroyLog(Project $project, $logId): JsonResponse {
        $log = TaskLog::findOrFail($logId);
        $log->delete();

        return response()->json(['success' => true]);
    }
}
