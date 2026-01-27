<div class="modal-header bg-warning">
    <h5 class="modal-title text-dark">{{ __('planning/view.risks.plan.title') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('projects.risks.plan.update', $project->project_id) }}" method="POST">
    @csrf
    <div class="modal-body bg-light" style="max-height: 80vh; overflow-y: auto;">
        <h6 class="text-center fw-bold mb-3 border-bottom pb-2">{{ __('planning/view.risks.plan.definitions_title') }}</h6>

        <div class="card mb-3 shadow-sm border-0">
            <div class="card-header bg-warning text-dark py-1 fw-bold text-center small">
                {{ __('planning/view.risks.plan.probability') }}
            </div>
            <div class="card-body p-2">
                @php
                    $levels = ['super_low', 'low', 'medium', 'high', 'super_high'];
                    $db_keys = ['super_low', 'low', 'medium', 'high', 'super_high'];
                @endphp

                @foreach($levels as $index => $level)
                    <div class="row mb-1 align-items-center">
                        <label class="col-sm-2 text-end small fw-bold text-muted">
                            {{ __('planning/view.risks.plan.levels.' . $level) }}
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="probability_{{ $db_keys[$index] }}"
                                   value="{{ $plan->{'probability_' . $db_keys[$index]} }}"
                                   class="form-control form-control-sm">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card mb-3 shadow-sm border-0">
            <div class="card-header bg-warning text-dark py-1 fw-bold text-center small">
                {{ __('planning/view.risks.plan.impact') }}
            </div>
            <div class="card-body p-2">
                @foreach($levels as $index => $level)
                    <div class="row mb-1 align-items-center">
                        <label class="col-sm-2 text-end small fw-bold text-muted">
                            {{ __('planning/view.risks.plan.levels.' . $level) }}
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="impact_{{ $db_keys[$index] }}"
                                   value="{{ $plan->{'impact_' . $db_keys[$index]} }}"
                                   class="form-control form-control-sm">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <h6 class="text-center fw-bold mb-2 mt-4 border-bottom pb-2">{{ __('planning/view.risks.plan.matrix_title') }}</h6>

        <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm text-center small align-middle">
                <thead>
                <tr class="bg-warning text-dark">
                    <th rowspan="2" class="align-middle">{{ __('planning/view.risks.plan.impact') }}</th>
                    <th colspan="5">{{ __('planning/view.risks.plan.probability') }}</th>
                </tr>
                <tr class="bg-light">
                    @foreach($levels as $level)
                        <th>{{ __('planning/view.risks.plan.matrix_headers.' . $level) }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($levels as $rowIdx => $rowLevel)
                    <tr>
                        <td class="fw-bold bg-light">{{ __('planning/view.risks.plan.matrix_headers.' . $rowLevel) }}</td>
                        @foreach($levels as $colIdx => $colLevel)
                            @php
                                $rowKey = str_replace('_', '', $db_keys[$rowIdx]);
                                $colKey = str_replace('_', '', $db_keys[$colIdx]);
                                $fieldName = "matrix_{$rowKey}_$colKey";

                                $currentValue = $plan->$fieldName;
                            @endphp
                            <td class="p-1">
                                <select name="{{ $fieldName }}" class="form-select form-select-sm" style="font-size: 0.75rem;">
                                    <option value="">-</option>
                                    @foreach($levels as $optLevel)
                                        @php
                                            $translatedLabel = __('planning/view.risks.plan.levels.' . $optLevel);
                                        @endphp
                                        <option value="{{ $translatedLabel }}"
                                            {{ $currentValue === $translatedLabel ? 'selected' : '' }}>
                                            {{ $translatedLabel }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <h6 class="text-center fw-bold mb-3 mt-4 border-bottom pb-2">{{ __('planning/view.risks.plan.monitoring_title') }}</h6>

        <div class="mb-2">
            <label class="fw-bold small">{{ __('planning/view.risks.plan.protocol') }}</label>
            <textarea name="risk_contengency_reserve_protocol" class="form-control form-control-sm" rows="3">{{ $plan->risk_contengency_reserve_protocol }}</textarea>
        </div>

        <div class="mb-2 row">
            <label class="col-sm-6 fw-bold small text-end pt-1">{{ __('planning/view.risks.plan.frequency') }}</label>
            <div class="col-sm-2">
                <input type="number" name="risk_revision_frequency" value="{{ $plan->risk_revision_frequency }}" class="form-control form-control-sm">
            </div>
        </div>

    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
    </div>
</form>
