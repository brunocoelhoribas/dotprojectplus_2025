<?php

namespace App\Http\Controllers\Initiating;

use App\Http\Controllers\Controller;
use App\Models\Initiating\Initiating;
use App\Models\Initiating\InitiatingStakeholder;
use App\Models\Project\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class InitiatingStakeholderController extends Controller {
    private function successResponse($translationKey): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => __($translationKey)
        ]);
    }

    /**
     * Store a newly created stakeholder in storage.
     */
    public function store(Request $request): JsonResponse {
        $validatedData = $this->validateStakeholder($request);
        InitiatingStakeholder::create($validatedData);

        $request->session()->flash('success', __('planning/messages.stakeholder.created'));

        return $this->successResponse('planning/messages.stakeholder.created');
    }

    public function update(Request $request, InitiatingStakeholder $stakeholder): JsonResponse {
        $validatedData = $this->validateStakeholder($request);

        $stakeholder->update($validatedData);
        $request->session()->flash('success', __('planning/messages.stakeholder.updated'));

        return $this->successResponse('planning/messages.stakeholder.updated');
    }

    public function destroy(InitiatingStakeholder $stakeholder): JsonResponse {
        $stakeholder->delete();

        request()->session()->flash('success', __('planning/messages.stakeholder.deleted'));

        return $this->successResponse('planning/messages.stakeholder.deleted');
    }

    public function generatePDF(Initiating $initiating): Response {
        $initiating->load('project', 'stakeholders.contact');

        $data = [
            'initiating' => $initiating
        ];

        $pdf = PDF::loadView('projects.pdf.stakeholders_pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('stakeholders_' . $initiating->project->project_name . '.pdf');
    }

    private function validateStakeholder(Request $request): array {
        return $request->validate([
            'initiating_id' => 'required|integer|exists:dotp_initiating,initiating_id',
            'contact_id' => 'required|integer|exists:dotp_contacts,contact_id',
            'stakeholder_responsibility' => 'nullable|string|max:100',
            'stakeholder_interest' => 'nullable|string|max:100',
            'stakeholder_power' => 'nullable|string|max:100',
            'stakeholder_strategy' => 'nullable|string|max:100',
        ]);
    }
}
