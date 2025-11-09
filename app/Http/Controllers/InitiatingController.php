<?php

namespace App\Http\Controllers;

use App\Models\Initiating\Initiating;
use App\Models\Project\Project;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class InitiatingController extends Controller {
    /**
     * Create or update the Project Charter (Initiating document).
     * (Based on do_initiating_aed.php)
     */
    public function storeOrUpdate(Request $request, Project $project): RedirectResponse {
        $validatedData = $request->validate([
            'initiating_manager' => 'nullable|exists:dotp_users,user_id',
            'initiating_justification' => 'nullable|string',
            'initiating_title' => 'nullable|string',
            'initiating_objective' => 'nullable|string',
            'initiating_expected_result' => 'nullable|string',
            'initiating_premise' => 'nullable|string',
            'initiating_restrictions' => 'nullable|string',
            'initiating_budget' => 'nullable|numeric',
            'initiating_start_date' => 'nullable|date',
            'initiating_end_date' => 'nullable|date|after_or_equal:initiating_start_date',
            'initiating_milestone' => 'nullable|string',
            'initiating_success' => 'nullable|string',
            'initiating_approved_comments' => 'nullable|string',
            'initiating_authorized_comments' => 'nullable|string',
        ]);

        // Find or create the initiating document
        $initiating = Initiating::firstOrNew(
            ['project_id' => $project->project_id]
        );

        // Fill data
        $initiating->fill($validatedData);

        // Set fields not in the form
        if (!$initiating->exists) {
            $initiating->initiating_create_by = auth()->id();
            $initiating->initiating_date_create = now();
            $initiating->initiating_title = $project->project_name; // from addedit.php
        }

        $initiating->save();

        // TODO: Implementar a lógica de 'action_authorized_performed'
        // (que copia dados do 'initiating' para o 'project')
        // if ($request->input('action_authorized_performed') == '1') { ... }

        return redirect()->back()->with('success', 'Termo de Abertura salvo com sucesso.');
    }
}
