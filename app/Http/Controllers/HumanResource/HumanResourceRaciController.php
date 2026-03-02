<?php

namespace App\Http\Controllers\HumanResource;

use App\Models\HumanResource\HumanResourceRaci;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Throwable;

class HumanResourceRaciController {
    public function store(Request $request, $companyId, $hrId): ?JsonResponse {
        $request->validate([
            'project_id' => 'required|integer',
            'activity_name' => 'required|string|max:255',
            'raci_role' => 'required|in:R,A,C,I'
        ]);

        try {
            HumanResourceRaci::create([
                'human_resource_id' => $hrId,
                'project_id' => $request->project_id,
                'activity_name' => $request->activity_name,
                'raci_role' => $request->raci_role
            ]);

            return response()->json(['success' => true, 'message' => __('companies/view.hr.messages.raci_created_success') ?? 'Papel RACI adicionado!']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => __('companies/view.hr.messages.error_title') . ': ' . $e->getMessage()], 500);
        }
    }

    public function destroy($companyId, $hrId, $raciId): ?JsonResponse {
        try {
            HumanResourceRaci::where('id', $raciId)->where('human_resource_id', $hrId)->delete();
            return response()->json(['success' => true, 'message' => __('companies/view.hr.messages.raci_deleted_success') ?? 'Removido com sucesso!']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => __('companies/view.hr.messages.error_title')], 500);
        }
    }
}
