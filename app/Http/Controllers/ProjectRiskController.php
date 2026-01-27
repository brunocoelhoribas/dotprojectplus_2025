<?php

namespace App\Http\Controllers;

use App\Models\Project\Project;
use App\Models\Project\ProjectRisk;
use App\Models\Project\ProjectRiskManagementPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectRiskController extends Controller {
    public function store(Request $request, Project $project): RedirectResponse {
        $validated = $this->validateAndSanitizeRisk($request);
        $validated['risk_project'] = $project->project_id;

        ProjectRisk::create($validated);

        return redirect()->route('projects.planning.tab', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'risks'
        ])->with('success', __('planning/messages.risk.created') ?? 'Risco criado com sucesso.');
    }

    public function update(Request $request, Project $project, ProjectRisk $risk): RedirectResponse {
        $validated = $this->validateAndSanitizeRisk($request);

        $risk->update($validated);

        return redirect()->route('projects.show', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'risks'
        ])->with('success', __('planning/messages.risk.updated') ?? 'Risco atualizado com sucesso.');
    }

    public function editPlan(Project $project) {
        $plan = ProjectRiskManagementPlan::firstOrNew(['project_id' => $project->project_id]);

        return view('projects.planning.tabs.risks.actions.plan_modal', [
            'project' => $project,
            'plan' => $plan
        ]);
    }

    public function updatePlan(Request $request, Project $project): RedirectResponse {
        $data = $request->except(['_token', '_method']);

        ProjectRiskManagementPlan::updateOrCreate(
            ['project_id' => $project->project_id],
            $data
        );

        return redirect()->route('projects.show', [
            'project' => $project->project_id,
            'tab' => 'planning',
            'subtab' => 'risks'
        ])->with('success', __('planning/view.risks.messages.plan_updated') ?? 'Plano atualizado com sucesso.');
    }

    private function validateAndSanitizeRisk(Request $request): array {
        $validated = $request->validate([
            'risk_name' => 'required|string|max:255',
            'risk_description' => 'required|string',
            'risk_cause' => 'nullable|string',
            'risk_consequence' => 'nullable|string',
            'risk_notes' => 'nullable|string',
            'risk_prevention_actions' => 'nullable|string',
            'risk_contingency_plan' => 'nullable|string',
            'risk_triggers' => 'nullable|string',
            'risk_period_start_date' => 'nullable|date',
            'risk_period_end_date' => 'nullable|date|after_or_equal:risk_period_start_date',
            'risk_task' => 'nullable|integer',
            'risk_ear_classification' => 'nullable|integer',
            'risk_responsible' => 'nullable|integer',
            'risk_strategy' => 'nullable|integer',
            'risk_status' => 'nullable|integer',
            'risk_probability' => 'nullable|integer|min:1|max:4',
            'risk_impact' => 'nullable|integer|min:1|max:4',
            'risk_potential_other_projects' => 'nullable|integer|in:0,1',
            'risk_is_contingency' => 'nullable|integer|in:0,1',
            'risk_active' => 'required|integer|in:0,1',
        ]);

        $textFields = [
            'risk_cause',
            'risk_consequence',
            'risk_notes',
            'risk_prevention_actions',
            'risk_contingency_plan',
            'risk_triggers'
        ];

        foreach ($textFields as $field) {
            if (!isset($validated[$field])) {
                $validated[$field] = '';
            }
        }

        return $validated;
    }

    public function checklist(Project $project) {
        $templates = ProjectRisk::where('risk_project', '!=', $project->project_id)
            ->where('risk_active', 1)
            ->orderBy('risk_name')
            ->get();

        return view('projects.planning.tabs.risks.actions.checklist', [
            'project' => $project,
            'templates' => $templates
        ]);
    }

    public function importChecklist(Request $request, Project $project): RedirectResponse {
        $selectedIds = $request->input('selected_risks', []);

        if (empty($selectedIds)) {
            return redirect()->route('projects.show', [
                'project' => $project->project_id, 'tab' => 'planning', 'subtab' => 'risks'
            ])->with('error', __('planning/view.risks.checklist.messages.empty_selection'));
        }

        $sourceRisks = ProjectRisk::whereIn('risk_id', $selectedIds)->get();

        foreach ($sourceRisks as $source) {
            $newRisk = $source->replicate();

            $newRisk->risk_project = $project->project_id;
            $newRisk->risk_task = 0;
            $newRisk->risk_responsible = 0;
            $newRisk->risk_status = 0;
            $newRisk->risk_active = 0;

            $newRisk->risk_notes = __('planning/view.risks.checklist.messages.imported_note', ['id' => $source->risk_project]);
            $newRisk->save();
        }

        return redirect()->route('projects.show', [
            'project' => $project->project_id, 'tab' => 'planning', 'subtab' => 'risks'
        ])->with('success', __('planning/view.risks.checklist.messages.success_imported', ['count' => count($sourceRisks)]));
    }

    public function watchList(Project $project) {
        $risks = ProjectRisk::where('risk_project', '<>', null)
            ->orderBy('risk_id')
            ->get();

        $activeRisks = $risks->filter(function ($risk) {
            $score = $risk->risk_probability * $risk->risk_impact;
            return $risk->risk_active === 1 && $score < 6;
        });

        $inactiveRisks = $risks->filter(function ($risk) {
            $score = $risk->risk_probability * $risk->risk_impact;
            return $risk->risk_active === 0 && $score < 6;
        });

        return view('projects.planning.tabs.risks.actions.watch_list', [
            'project' => $project,
            'activeRisks' => $activeRisks,
            'inactiveRisks' => $inactiveRisks
        ]);
    }

    public function shortTermList(Project $project) {
        $risks = ProjectRisk::where('risk_project', '<>', null)
            ->orderBy('risk_id')
            ->get();

        $activeRisks = $risks->filter(function ($risk) {
            $score = $risk->risk_probability * $risk->risk_impact;
            return $risk->risk_active === 1 && $score >= 6;
        });

        $inactiveRisks = $risks->filter(function ($risk) {
            $score = $risk->risk_probability * $risk->risk_impact;
            return $risk->risk_active === 0 && $score >= 6;
        });

        return view('projects.planning.tabs.risks.actions.short_term', [
            'project' => $project,
            'activeRisks' => $activeRisks,
            'inactiveRisks' => $inactiveRisks
        ]);
    }

    public function lessonsLearnedList(Project $project) {
        $risks = ProjectRisk::where('risk_project', '<>', null)
            ->orderBy('risk_id')
            ->get();

        $activeRisks = $risks->where('risk_active', 1);
        $inactiveRisks = $risks->where('risk_active', 0);

        return view('projects.planning.tabs.risks.actions.lessons_learned', [
            'project' => $project,
            'activeRisks' => $activeRisks,
            'inactiveRisks' => $inactiveRisks
        ]);
    }
}
