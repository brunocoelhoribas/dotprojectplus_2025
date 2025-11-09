<?php

namespace App\Http\Controllers;

use App\Models\Initiating\InitiatingStakeholder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class InitiatingStakeholderController extends Controller {
    public function store(Request $request): RedirectResponse {
        $validatedData = $this->validateStakeholder($request);

        InitiatingStakeholder::create($validatedData);

        return redirect()->back()->with('success', 'Stakeholder adicionado com sucesso.');
    }

    public function update(Request $request, InitiatingStakeholder $stakeholder): RedirectResponse {
        $validatedData = $this->validateStakeholder($request);

        $stakeholder->update($validatedData);

        return redirect()->back()->with('success', 'Stakeholder atualizado com sucesso.');
    }

    public function destroy(InitiatingStakeholder $stakeholder): RedirectResponse {
        $stakeholder->delete();

        return redirect()->back()->with('success', 'Stakeholder excluído com sucesso.');
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
