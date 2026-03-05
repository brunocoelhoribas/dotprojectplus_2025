<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Company\CompanyOrganogramRole;
use App\Models\HumanResource\HumanResourcesRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JsonException;
use Throwable;

class CompanyOrganogramController extends Controller {

    /**
     * @throws Throwable
     * @throws JsonException
     */
    public function update(Request $request, Company $company): JsonResponse {
        $organogramData = json_decode($request->input('organogram_data'), true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($organogramData)) {
            return response()->json([
                'success' => false,
                'message' => __('companies/view.messages.organogram_invalid')
            ], 422);
        }

        try {
            DB::transaction(static function () use ($organogramData, $company) {
                CompanyOrganogramRole::where('company_id', $company->company_id)->delete();

                foreach ($organogramData as $index => $row) {
                    if (!empty($row['role_name'])) {
                        CompanyOrganogramRole::create([
                            'company_id' => $company->company_id,
                            'sort_order' => $index,
                            'identation' => $row['level'],
                            'role_name'  => $row['role_name']
                        ]);
                    }
                }
            });
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => __('companies/view.messages.organogram_success')
        ]);
    }
}
