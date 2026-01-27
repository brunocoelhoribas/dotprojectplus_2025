<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRiskRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'risk_name' => 'required|string|max:255',
            'risk_description' => 'required|string',
            'risk_cause' => 'nullable|string',
            'risk_consequence' => 'nullable|string',
            'risk_notes' => 'nullable|string',
            'risk_prevention_actions' => 'nullable|string',
            'risk_contingency_plan' => 'nullable|string',
            'risk_triggers' => 'nullable|string',
            'risk_period_start_date' => 'nullable|date',
            'risk_period_end_date' => 'nullable|date|after_or_equal:risk_period_start_date',
            'risk_task' => 'nullable|integer',
            'risk_ear_classification' => 'nullable|integer',
            'risk_responsible' => 'nullable|integer',
            'risk_strategy' => 'nullable|integer',
            'risk_status' => 'nullable|integer',
            'risk_probability' => 'nullable|integer|min:1|max:4',
            'risk_impact' => 'nullable|integer|min:1|max:4',
            'risk_potential_other_projects' => 'nullable|integer|in:0,1',
            'risk_is_contingency' => 'nullable|integer|in:0,1',
            'risk_active' => 'required|integer|in:0,1',
        ];
    }

    protected function prepareForValidation(): void {
        $textFields = [
            'risk_cause', 'risk_consequence', 'risk_notes',
            'risk_prevention_actions', 'risk_contingency_plan', 'risk_triggers'
        ];

        $input = $this->all();

        foreach ($textFields as $field) {
            if (!isset($input[$field])) {
                $input[$field] = '';
            }
        }

        $this->replace($input);
    }
}
