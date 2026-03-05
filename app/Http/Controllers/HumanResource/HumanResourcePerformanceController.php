<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Models\HumanResource\HumanResourcePerformance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Throwable;

class HumanResourcePerformanceController extends Controller
{
    public function store(Request $request, $companyId): JsonResponse
    {
        $request->validate([
            'human_resource_id' => 'required|integer',
            'performance_score' => 'required|integer|min:1|max:3',
            'potential_score' => 'required|integer|min:1|max:3',
            'facilitator_notes' => 'nullable|string'
        ]);

        try {
            HumanResourcePerformance::updateOrCreate(
                [
                    'company_id' => $companyId,
                    'human_resource_id' => $request->human_resource_id,
                ],
                [
                    'performance_score' => $request->performance_score,
                    'potential_score' => $request->potential_score,
                    'facilitator_notes' => $request->facilitator_notes,
                    'evaluation_date' => Carbon::now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => __('Avaliação salva com sucesso na Matriz 9-Box!')
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($companyId, $hrId): JsonResponse
    {
        try {
            HumanResourcePerformance::where('company_id', $companyId)
                ->where('human_resource_id', $hrId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => __('Recurso removido da matriz de avaliação.')
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
