<?php

namespace App\Http\Controllers\HumanResource;

use App\Models\HumanResource\HumanResource;
use App\Models\HumanResource\HumanResourceRaci;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HumanResourceRaciController
{

    public function index(Request $request, $companyId): JsonResponse
    {
        $hrIds = HumanResource::whereHas('user', function ($q) use ($companyId) {
            $q->where('user_company', $companyId);
        })->pluck('human_resource_id')->toArray();

        $projectIds = DB::table('dotp_projects')
            ->where('project_company', $companyId)
            ->pluck('project_id')
            ->toArray();

        $allRaci = HumanResourceRaci::whereIn('human_resource_id', $hrIds)
            ->whereIn('project_id', $projectIds)
            ->get(['id', 'human_resource_id', 'project_id', 'activity_name', 'raci_role']);

        return response()->json(['success' => true, 'raci' => $allRaci]);
    }

    public function store(Request $request, $companyId, $hrId): ?JsonResponse
    {
        $request->validate([
            'project_id' => 'required|integer',
            'activity_name' => 'required|string|max:255',
            'raci_role' => 'required|in:R,A,C,I'
        ]);

        try {
            $record = HumanResourceRaci::create([
                'human_resource_id' => $hrId,
                'project_id' => $request->project_id,
                'activity_name' => $request->activity_name,
                'raci_role' => $request->raci_role
            ]);

            return response()->json([
                'success' => true,
                'record' => $record,
                'message' => __('companies/view.hr.messages.raci_created_success') ?? 'Papel RACI adicionado!'
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => __('companies/view.hr.messages.error_title') . ': ' . $e->getMessage()], 500);
        }
    }

    public function destroy($companyId, $hrId, $raciId): ?JsonResponse
    {
        try {
            HumanResourceRaci::where('id', $raciId)->where('human_resource_id', $hrId)->delete();
            return response()->json(['success' => true, 'message' => __('companies/view.hr.messages.raci_deleted_success') ?? 'Removido com sucesso!']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => __('companies/view.hr.messages.error_title')], 500);
        }
    }
}
