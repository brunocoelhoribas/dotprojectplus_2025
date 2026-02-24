<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\HumanResource\HumanResource;
use App\Models\HumanResource\HumanResourceSkill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class HumanResourceSkillController extends Controller {
    /**
     * @throws Throwable
     */
    public function store(Request $request, $hrId): ?JsonResponse {
        $request->validate([
            'skill_name' => 'required|string|max:50',
            'skill_type' => 'required|in:technical,behavioral',
            'proficiency_level' => 'required|integer|min:1|max:5'
        ]);

        try {
            DB::beginTransaction();
            $skill = HumanResourceSkill::query()->firstOrCreate(
                ['skill_name' => $request->skill_name],
                ['skill_type' => $request->skill_type]
            );

            $hr = HumanResource::findOrFail($hrId);
            $hr->skills()->syncWithoutDetaching([
                $skill->skill_id => ['proficiency_level' => $request->proficiency_level]
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => __('companies/view.hr.messages.skill_created_success')]);

        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => __('companies/view.hr.messages.error_title') . ': ' . $e->getMessage()], 500);
        }
    }

    public function destroy($hrId, $skillId): ?JsonResponse {
        try {
            $hr = HumanResource::findOrFail($hrId);
            $hr->skills()->detach($skillId);
            return response()->json(['success' => true, 'message' => __('companies/view.hr.messages.skill_deleted_success')]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => __('companies/view.hr.messages.skill_deleted_error')], 500);
        }
    }
}
