<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Models\Planning\Cost\Cost;
use App\Models\Project\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanningCostController extends Controller {
    private function successResponse($translationKey): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => __($translationKey)
        ]);
    }

    public function store(Request $request, Project $project): JsonResponse {
        $data = $request->validate([
            'cost_description' => 'required|string|max:150',
            'cost_quantity' => 'required|integer|min:1',
            'cost_value_unitary' => 'required|numeric|min:0',
            'cost_date_begin' => 'nullable|date',
            'cost_date_end' => 'nullable|date|after_or_equal:cost_date_begin',
        ]);

        $data['cost_project_id'] = $project->project_id;
        $data['cost_type_id'] = 2;
        $data['cost_value_total'] = $data['cost_quantity'] * $data['cost_value_unitary'];

        Cost::create($data);

        return $this->successResponse('planning/messages.cost.created');
    }

    public function update(Request $request, Project $project, Cost $cost): JsonResponse {
        $data = $request->validate([
            'cost_description' => 'required|string|max:150',
            'cost_quantity' => 'required|integer|min:1',
            'cost_value_unitary' => 'required|numeric|min:0',
            'cost_date_begin' => 'nullable|date',
            'cost_date_end' => 'nullable|date|after_or_equal:cost_date_begin',
        ]);

        $data['cost_value_total'] = $data['cost_quantity'] * $data['cost_value_unitary'];
        $cost->update($data);

        return $this->successResponse('planning/messages.cost.updated');
    }


    public function destroy(Project $project, Cost $cost): JsonResponse {
        $cost->delete();

        return $this->successResponse('planning/messages.cost.deleted');
    }
}
