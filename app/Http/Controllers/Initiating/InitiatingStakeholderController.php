<?php

namespace App\Http\Controllers\Initiating;

use App\Http\Controllers\Controller;
use App\Models\Initiating\Initiating;
use App\Models\Initiating\InitiatingStakeholder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InitiatingStakeholderController extends Controller {
    public function store(Request $request): RedirectResponse {
        $validatedData = $this->validateStakeholder($request);

        InitiatingStakeholder::create($validatedData);

        // ALTERADO: Mensagem traduzida
        return redirect()->back()->with('success', __('initiating/messages.stakeholder.created'));
    }

    public function update(Request $request, InitiatingStakeholder $stakeholder): RedirectResponse {
        $validatedData = $this->validateStakeholder($request);

        $stakeholder->update($validatedData);

        return redirect()->back()->with('success', __('initiating/messages.stakeholder.updated'));
    }

    public function destroy(InitiatingStakeholder $stakeholder): RedirectResponse {
        $stakeholder->delete();

        return redirect()->back()->with('success', __('initiating/messages.stakeholder.deleted'));
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
