<?php

namespace App\Http\Controllers\Closure;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use App\Models\Project\ProjectClosure;
use App\Models\User\User;
use Illuminate\Http\Request;

class ClosureController extends Controller {
    public function show(Request $request, Project $project) {
        $availableUsers = User::whereHas('contact', function ($query) use ($project) {
            $query->where('contact_company', $project->project_company);
        })->with('contact')->get();

        $closure = ProjectClosure::where('project_name', $project->project_name)->first();

        if (!$closure) {
            $closure = new ProjectClosure();
            $closure->project_name = $project->project_name;
            $closure->planned_budget = $project->project_target_budget;
            $closure->project_planned_start_date = $project->project_start_date;
            $closure->project_planned_end_date = $project->project_end_date;
            $closure->project_start_date = $project->project_actual_start_date ?? null;
            $closure->project_end_date = $project->project_actual_end_date ?? null;
            $closure->budget = 0;
        }

        if ($request->ajax()) {
            return view('projects.tabs.closure', compact('project', 'closure', 'availableUsers'))->render();
        }

        return view('projects.tabs.closure', compact('project', 'closure', 'availableUsers'));
    }

    public function store(Request $request, Project $project) {
        $data = $request->validate([
            'project_meeting_date' => 'nullable|date',
            'participants' => 'nullable|string',
            'project_planned_start_date' => 'nullable|date',
            'project_start_date' => 'nullable|date',
            'project_planned_end_date' => 'nullable|date',
            'project_end_date' => 'nullable|date',
            'planned_budget' => 'nullable|numeric',
            'budget' => 'nullable|numeric',
            'project_strength' => 'nullable|string',
            'project_weaknesses' => 'nullable|string',
            'improvement_suggestions' => 'nullable|string',
            'conclusions' => 'nullable|string',
        ]);

        $data['project_name'] = $project->project_name;

        ProjectClosure::updateOrCreate(
            ['project_name' => $project->project_name],
            $data
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('closure.messages.saved')
            ]);
        }

        return back()->with('success', __('closure.messages.saved'));
    }
}
