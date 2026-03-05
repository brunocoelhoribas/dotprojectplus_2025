<?php

namespace App\Http\Controllers\Planning\Acquisition;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use App\Models\Planning\Acquisition\AcquisitionPlanning;
use App\Models\Planning\Acquisition\AcquisitionCriteria;
use App\Models\Planning\Acquisition\AcquisitionRequirement;
use App\Models\Planning\Acquisition\AcquisitionRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcquisitionController extends Controller {
    private function successResponse($key): JsonResponse {
        return response()->json(['success' => true, 'message' => __($key)]);
    }

    public function store(Request $request, Project $project): JsonResponse {
        $this->saveOrUpdate($request, $project);
        return $this->successResponse('planning/messages.acquisition.created');
    }

    public function update(Request $request, Project $project, AcquisitionPlanning $acquisition): JsonResponse {
        $this->saveOrUpdate($request, $project, $acquisition);
        return $this->successResponse('planning/messages.acquisition.updated');
    }

    private function saveOrUpdate(Request $request, Project $project, $acquisition = null): void {
        $data = $request->only([
            'items_to_be_acquired', 'contract_type',
            'documents_to_acquisition', 'supplier_management_process'
        ]);
        $data['project_id'] = $project->project_id;

        if ($acquisition) {
            $acquisition->update($data);
        } else {
            $acquisition = AcquisitionPlanning::create($data);
        }

        $acquisition->criteria()->delete();
        if ($request->has('criteria_name')) {
            foreach ($request->criteria_name as $index => $name) {
                if ($name) {
                    AcquisitionCriteria::create([
                        'acquisition_id' => $acquisition->id,
                        'criteria' => $name,
                        'weight' => $request->criteria_weight[$index] ?? 0
                    ]);
                }
            }
        }

        $acquisition->requirements()->delete();
        if ($request->has('req_name')) {
            foreach ($request->req_name as $name) {
                if ($name) {
                    AcquisitionRequirement::create([
                        'acquisition_id' => $acquisition->id,
                        'requirement' => $name
                    ]);
                }
            }
        }

        $acquisition->roles()->delete();
        if ($request->has('role_name')) {
            foreach ($request->role_name as $index => $name) {
                if ($name) {
                    AcquisitionRole::create([
                        'acquisition_id' => $acquisition->id,
                        'role' => $name,
                        'responsability' => $request->role_resp[$index] ?? ''
                    ]);
                }
            }
        }
    }

    public function show(Project $project, AcquisitionPlanning $acquisition): JsonResponse {
        $acquisition->load(['criteria', 'requirements', 'roles']);
        return response()->json($acquisition);
    }

    public function destroy(Project $project, AcquisitionPlanning $acquisition) {
        $acquisition->criteria()->delete();
        $acquisition->requirements()->delete();
        $acquisition->roles()->delete();
        $acquisition->delete();

        return $this->successResponse('planning/messages.acquisition.deleted');
    }
}
