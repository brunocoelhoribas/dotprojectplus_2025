<?php

namespace App\Http\Controllers;

use App\Models\Project\Project;
use App\Models\Project\ProjectWbsItem;
use App\Models\Project\Task\Task;
use App\Models\Project\Task\TasksWorkpackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class PlanningController extends Controller {

    public function index(Request $request, Project $project): View {

        $wbsItems = ProjectWbsItem::with([
            'tasks.owner.contact',
            'tasks.estimation'
        ])
            ->where('project_id', $project->project_id)
            ->orderBy('sort_order')
            ->get();

        return view('projects.planning.index', [
            'project' => $project,
            'wbsItems' => $wbsItems,
        ]);
    }

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
                'identation' => $newIndentation,
            ]);

            $parentItem->update(['is_leaf' => 0]);
        });

        return redirect()->back()->with('success', 'Item EAP criado com sucesso.');
    }

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

            // Opcional: Criar registro vazio em dotp_project_tasks_estimations se necessário
        });

        return redirect()->back()->with('success', 'Atividade criada com sucesso.');
    }

    public static function numberToAlpha($n): string {
        $r = '';
        for ($i = 1; $n >= 0 && $i < 10; $i++) {
            $r = chr(97 + ($n % 26)) . $r;
            $n = (int)($n / 26) - 1;
        }
        return $r;
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
        return redirect()->back()->with('success', 'Atividade atualizada com sucesso.');
    }


    /**
     * Reordena um item da EAP (e seus filhos) para cima ou para baixo.
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

        return redirect()->back();
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
            ->orderBy('task_order', 'asc')
            ->orderBy('task_start_date', 'asc')
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

        return redirect()->back();
    }

    public function destroyWbsItem(Project $project, ProjectWbsItem $wbsItem): RedirectResponse {
        DB::transaction(static function () use ($project, $wbsItem) {
            $allBelow = ProjectWbsItem::where('project_id', $project->project_id)
                ->where('sort_order', '>', $wbsItem->sort_order)
                ->orderBy('sort_order', 'asc')
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

        return redirect()->back()->with('success', 'Item da EAP e sub-itens excluídos com sucesso.');
    }

    public function destroyActivity(Project $project, Task $task): RedirectResponse {
        DB::transaction(static function () use ($task) {
            TasksWorkpackage::where('task_id', $task->task_id)->delete();
            $task->delete();
        });

        return redirect()->back()->with('success', 'Atividade excluída com sucesso.');
    }
}
