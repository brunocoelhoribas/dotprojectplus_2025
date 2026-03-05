<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\Planning\Cost\Cost;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Throwable;

class UserCostController extends Controller {
    public function store(Request $request, User $user): JsonResponse {
        $request->validate([
            'cost_project_id' => 'required|integer',
            'cost_date_begin' => 'required|date',
            'cost_date_end' => 'nullable|date|after_or_equal:cost_date_begin',
            'cost_value' => 'required|numeric|min:0',
        ]);

        try {
            Cost::create([
                'cost_human_resource_id' => $user->user_id,
                'cost_human_resource_role_id' => null,
                'cost_project_id' => $request->cost_project_id,
                'cost_date_begin' => $request->cost_date_begin,
                'cost_date_end' => $request->cost_date_end,
                'cost_value_unitary' => $request->cost_value,
                'cost_value_total' => $request->cost_value,
                'cost_quantity' => 1,
                'cost_type_id' => 0,
                'cost_description' => 'Taxa de HR'
            ]);

            return response()->json(['success' => true, 'message' => __('companies/view.hr.messages.created_success')]);

        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Erro DB: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, User $user, $costId): JsonResponse {
        $request->validate([
            'cost_project_id' => 'required|integer',
            'cost_date_begin' => 'required|date',
            'cost_date_end' => 'nullable|date|after_or_equal:cost_date_begin',
            'cost_value' => 'required|numeric|min:0',
        ]);

        try {
            $cost = Cost::where('cost_human_resource_id', $user->user_id)->findOrFail($costId);

            $cost->update([
                'cost_project_id' => $request->cost_project_id,
                'cost_date_begin' => $request->cost_date_begin,
                'cost_date_end' => $request->cost_date_end,
                'cost_value_unitary' => $request->cost_value,
                'cost_value_total' => $request->cost_value,
            ]);

            return response()->json(['success' => true, 'message' => __('companies/view.hr.messages.updated_success')]);

        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Erro DB: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(User $user, $costId): JsonResponse {
        try {
            Cost::where('cost_human_resource_id', $user->user_id)->findOrFail($costId)->delete();
            return response()->json(['success' => true, 'message' => __('companies/view.hr.messages.deleted_success')]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Erro DB: ' . $e->getMessage()], 500);
        }
    }
}
