<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRiskRequest;
use App\Models\Project\Project;
use App\Models\Project\ProjectRisk;
use App\Models\Project\ProjectRiskManagementPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectRiskController extends Controller {
    private function redirectBackToTab(Project $project, string $message, string $type = 'success'): RedirectResponse {
        return redirect()->route('projects.show', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'risks'
        ])->with($type, $message);
    }

    public function store(StoreProjectRiskRequest $request, Project $project): RedirectResponse {
        $data = $request->validated();
        $data['risk_project'] = $project->project_id;

        ProjectRisk::create($data);

        return $this->redirectBackToTab($project, __('planning/messages.risk.created') ?? 'Risco criado com sucesso.');
    }

    public function update(StoreProjectRiskRequest $request, Project $project, ProjectRisk $risk): RedirectResponse {
        $risk->update($request->validated());

        return $this->redirectBackToTab($project, __('planning/messages.risk.updated') ?? 'Risco atualizado com sucesso.');
    }

    public function editPlan(Project $project): View {
        $plan = ProjectRiskManagementPlan::firstOrNew(['project_id' => $project->project_id]);

        return view('projects.planning.tabs.risks.actions.plan_modal', compact('project', 'plan'));
    }

    public function updatePlan(Request $request, Project $project): RedirectResponse {
        $data = $request->except(['_token', '_method']);

        ProjectRiskManagementPlan::updateOrCreate(
            ['project_id' => $project->project_id],
            $data
        );

        return $this->redirectBackToTab($project, __('planning/view.risks.messages.plan_updated') ?? 'Plano atualizado com sucesso.');
    }

    public function checklist(Project $project): View {
        $templates = ProjectRisk::where('risk_project', '!=', $project->project_id)
            ->where('risk_active', ProjectRisk::ACTIVE)
            ->orderBy('risk_name')
            ->get();

        return view('projects.planning.tabs.risks.actions.checklist', compact('project', 'templates'));
    }

    public function importChecklist(Request $request, Project $project): RedirectResponse {
        $selectedIds = $request->input('selected_risks', []);

        if (empty($selectedIds)) {
            return $this->redirectBackToTab($project, __('planning/view.risks.checklist.messages.empty_selection'), 'error');
        }

        $sourceRisks = ProjectRisk::whereIn('risk_id', $selectedIds)->get();

        foreach ($sourceRisks as $source) {
            $newRisk = $source->replicate();
            $newRisk->fill([
                'risk_project' => $project->project_id,
                'risk_task' => 0,
                'risk_responsible' => 0,
                'risk_status' => 0,
                'risk_active' => ProjectRisk::ACTIVE,
                'risk_notes' => __('planning/view.risks.checklist.messages.imported_note', ['id' => $source->risk_project]),
            ]);
            $newRisk->save();
        }

        return $this->redirectBackToTab($project,
            __('planning/view.risks.checklist.messages.success_imported', ['count' => count($sourceRisks)])
        );
    }

    private function getProjectRisks(Project $project) {
        return ProjectRisk::where('risk_project', '<>', null)
            ->orderBy('risk_id')
            ->get();
    }

    public function watchList(Project $project): View {
        $risks = $this->getProjectRisks($project);

        $activeRisks = $risks->filter(fn($r) => $r->risk_active === ProjectRisk::ACTIVE && !$r->isHighPriority());
        $inactiveRisks = $risks->filter(fn($r) => $r->risk_active === ProjectRisk::INACTIVE && !$r->isHighPriority());

        return view('projects.planning.tabs.risks.actions.watch_list', compact('project', 'activeRisks', 'inactiveRisks'));
    }

    public function shortTermList(Project $project): View {
        $risks = $this->getProjectRisks($project);

        $activeRisks = $risks->filter(fn($r) => $r->risk_active === ProjectRisk::ACTIVE && $r->isHighPriority());
        $inactiveRisks = $risks->filter(fn($r) => $r->risk_active !== ProjectRisk::ACTIVE && $r->isHighPriority());

        return view('projects.planning.tabs.risks.actions.short_term', compact('project', 'activeRisks', 'inactiveRisks'));
    }

    public function lessonsLearnedList(Project $project): View {
        $risks = $this->getProjectRisks($project);

        $activeRisks = $risks->where('risk_active', ProjectRisk::ACTIVE);
        $inactiveRisks = $risks->where('risk_active', '!=', ProjectRisk::ACTIVE);

        return view('projects.planning.tabs.risks.actions.lessons_learned', compact('project', 'activeRisks', 'inactiveRisks'));
    }

    public function responseList(Project $project): View {
        $risks = $this->getProjectRisks($project);

        $activeRisks = $risks->where('risk_active', ProjectRisk::ACTIVE);
        $inactiveRisks = $risks->where('risk_active', '!=', ProjectRisk::ACTIVE);

        return view('projects.planning.tabs.risks.actions.response_list', compact('project', 'activeRisks', 'inactiveRisks'));
    }
}
