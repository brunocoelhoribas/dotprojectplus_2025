<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use App\Models\Project\Quality\QualityAssuranceItem;
use App\Models\Project\Quality\QualityGoal;
use App\Models\Project\Quality\QualityAnalysisQuestion;
use App\Models\Project\Quality\QualityMetric;
use App\Models\Project\Quality\QualityPlanning;
use App\Models\Project\Quality\QualityRequirement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QualityController extends Controller {
    private function successResponse($translationKey): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => __($translationKey)
        ]);
    }

    public function updatePlan(Request $request, Project $project): JsonResponse {
        $plan = QualityPlanning::firstOrCreate(['project_id' => $project->project_id]);

        $plan->update($request->only([
            'quality_policies',
            'quality_assurance',
            'quality_controlling'
        ]));

        return $this->successResponse('planning/messages.quality.plan_updated');
    }

    public function storeRequirement(Request $request, Project $project): JsonResponse {
        $plan = QualityPlanning::firstOrCreate(['project_id' => $project->project_id]);

        QualityRequirement::create([
            'quality_planning_id' => $plan->id,
            'requirement' => $request->input('requirement')
        ]);

        return $this->successResponse('planning/messages.quality.requirement_added');
    }

    public function destroyRequirement(Project $project, QualityRequirement $requirement): JsonResponse {
        $requirement->delete();
        return $this->successResponse('planning/messages.quality.requirement_removed');
    }

    public function storeAssurance(Request $request, Project $project): JsonResponse {
        $plan = QualityPlanning::firstOrCreate(['project_id' => $project->project_id]);

        QualityAssuranceItem::create([
            'quality_planning_id' => $plan->id,
            'what' => $request->input('what'),
            'who' => $request->input('who'),
            'when' => $request->input('when'),
            'how' => $request->input('how'),
        ]);

        return $this->successResponse('planning/messages.quality.assurance_added');
    }

    public function destroyAssurance(Project $project, QualityAssuranceItem $item): JsonResponse {
        $item->delete();
        return $this->successResponse('planning/messages.quality.assurance_removed');
    }

    // --- GQM ---

    public function storeGoal(Request $request, Project $project): JsonResponse {
        $plan = QualityPlanning::firstOrCreate(['project_id' => $project->project_id]);
        QualityGoal::create(array_merge($request->all(), ['quality_planning_id' => $plan->id]));

        return $this->successResponse('planning/messages.quality.goal_added');
    }

    public function destroyGoal(Project $project, QualityGoal $goal): JsonResponse {
        $goal->delete();
        return $this->successResponse('planning/messages.quality.goal_removed');
    }

    public function storeQuestion(Request $request, Project $project, QualityGoal $goal): JsonResponse {
        QualityAnalysisQuestion::create(array_merge($request->all(), ['goal_id' => $goal->id]));

        return $this->successResponse('planning/messages.quality.question_added');
    }

    public function destroyQuestion(Project $project, QualityAnalysisQuestion $question): JsonResponse {
        $question->delete();
        return $this->successResponse('planning/messages.quality.question_removed');
    }

    public function storeMetric(Request $request, Project $project, QualityAnalysisQuestion $question) {
        QualityMetric::create(array_merge($request->all(), ['question_id' => $question->id]));

        return $this->successResponse('planning/messages.quality.metric_added');
    }

    public function destroyMetric(Project $project, QualityMetric $metric): JsonResponse {
        $metric->delete();
        return $this->successResponse('planning/messages.quality.metric_removed');
    }
}
