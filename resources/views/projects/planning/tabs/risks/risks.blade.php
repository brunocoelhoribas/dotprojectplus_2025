<div class="mb-4">
    <div class="d-flex flex-wrap gap-2 justify-content-end mb-3">
        <button class="btn btn-sm btn-outline-secondary" onclick="openManagementPlanModal()">
            {{ __('planning/view.risks.actions.management_plan') }}
        </button>
        <button class="btn btn-sm btn-outline-secondary" onclick="openChecklistModal()">
            {{ __('planning/view.risks.actions.checklist_analysis') }}
        </button>
        <button class="btn btn-sm btn-outline-secondary" onclick="openWatchListModal()">
            {{ __('planning/view.risks.actions.watch_list') }}
        </button>
        <button class="btn btn-sm btn-outline-secondary" onclick="openShortTermModal()">
            {{ __('planning/view.risks.actions.short_term_response') }}
        </button>
        <button class="btn btn-sm btn-outline-secondary" onclick="openLessonsLearnedModal()">
            {{ __('planning/view.risks.actions.lessons_learned') }}
        </button>
        <button class="btn btn-sm btn-outline-secondary" onclick="openResponseListModal()">
            {{ __('planning/view.risks.actions.response_list') }}
        </button>
        <button class="btn btn-sm btn-secondary" onclick="openNewRiskModal()">
            <i class="bi bi-plus-lg me-1"></i> {{ __('planning/view.risks.actions.new_risk') }}
        </button>
    </div>

    <h5 class="text-white bg-secondary p-2 rounded-top fs-6 mb-0">{{ __('planning/view.risks.active_risks') }}</h5>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-hover table-sm align-middle mb-0">
            <thead class="table-warning">
            <tr>
                <th style="width: 70px;" class="text-center">{{ __('planning/view.risks.table.actions') }}</th>
                <th style="width: 40px;" class="text-center">{{ __('planning/view.risks.table.id') }}</th>
                <th>{{ __('planning/view.risks.table.title') }}</th>
                <th>{{ __('planning/view.risks.table.description') }}</th>
                <th>{{ __('planning/view.risks.table.probability') }}</th>
                <th>{{ __('planning/view.risks.table.impact') }}</th>
                <th>{{ __('planning/view.risks.table.exposure_factor') }}</th>
                <th>{{ __('planning/view.risks.table.status') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($activeRisks as $risk)
                <tr>
                    <td class="text-center">
                        <button class="btn btn-xs btn-link text-primary p-0" title="Editar"
                                onclick="openEditRiskModal({{ $risk }})">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-xs btn-link text-dark p-0" title="Visualizar"
                                onclick="openViewRiskModal({{ $risk }})">
                            <i class="bi bi-journal-text"></i>
                        </button>
                    </td>
                    <td class="text-center">{{ $risk->risk_id }}</td>
                    <td>{{ $risk->risk_name }}</td>
                    <td>{{ Str::limit($risk->risk_description, 50) }}</td>

                    <td>{{ __('planning/view.risks.levels.' . $risk->probability_text) }}</td>
                    <td>{{ __('planning/view.risks.levels.' . $risk->impact_text) }}</td>

                    <td class="{{ $risk->exposure_factor_level }} fw-bold">
                        {{ __('planning/view.risks.levels.' . $risk->risk_exposure) }}
                    </td>

                    <td>{{ __('planning/view.risks.status.' . $risk->status_key) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-3 text-muted">{{ __('planning/view.risks.table.empty') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($inactiveRisks->isNotEmpty())
        <h5 class="text-white bg-secondary p-2 rounded-top fs-6 mb-0 bg-opacity-75">{{ __('planning/view.risks.inactive_risks') }}</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle mb-0">
                <thead class="table-warning">
                <tr>
                    <th style="width: 70px;" class="text-center">{{ __('planning/view.risks.table.actions') }}</th>
                    <th style="width: 40px;" class="text-center">{{ __('planning/view.risks.table.id') }}</th>
                    <th>{{ __('planning/view.risks.table.title') }}</th>
                    <th>{{ __('planning/view.risks.table.description') }}</th>
                    <th>{{ __('planning/view.risks.table.probability') }}</th>
                    <th>{{ __('planning/view.risks.table.impact') }}</th>
                    <th>{{ __('planning/view.risks.table.exposure_factor') }}</th>
                    <th>{{ __('planning/view.risks.table.status') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($inactiveRisks as $risk)
                    <tr>
                        <td class="text-center">
                            <button class="btn btn-xs btn-link text-primary p-0" title="Editar"
                                    onclick="openEditRiskModal({{ $risk }})">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-xs btn-link text-dark p-0" title="Visualizar"
                                    onclick="openViewRiskModal({{ $risk }})">
                                <i class="bi bi-journal-text"></i>
                            </button>
                        </td>
                        <td class="text-center">{{ $risk->risk_id }}</td>
                        <td>{{ $risk->risk_name }}</td>
                        <td>{{ Str::limit($risk->risk_description, 50) }}</td>

                        <td>{{ __('planning/view.risks.levels.' . $risk->probability_text) }}</td>
                        <td>{{ __('planning/view.risks.levels.' . $risk->impact_text) }}</td>

                        <td class="{{ $risk->exposure_factor_level }} fw-bold">
                            {{ __('planning/view.risks.levels.' . $risk->risk_exposure) }}
                        </td>

                        <td>{{ __('planning/view.risks.status.' . $risk->status_key) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="modal fade" id="riskModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="riskForm" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->project_id }}">
                <div id="method_field"></div>

                <div class="modal-header">
                    <h5 class="modal-title" id="riskModalTitle">Editar Risco</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-light">
                    <h6 class="border-bottom border-secondary pb-1 mb-3 fw-bold text-uppercase text-secondary"
                        style="font-size: 0.8rem;">
                        {{ __('planning/view.risks.form.section_id') }}
                    </h6>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.table.title') }}
                            *:</label>
                        <div class="col-sm-10">
                            <input type="text" name="risk_name" id="risk_name" class="form-control form-control-sm"
                                   required>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.cause') }}
                            :</label>
                        <div class="col-sm-10">
                            <textarea name="risk_cause" id="risk_cause" class="form-control form-control-sm"
                                      rows="1"></textarea>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.consequence') }}
                            :</label>
                        <div class="col-sm-10">
                            <textarea name="risk_consequence" id="risk_consequence" class="form-control form-control-sm"
                                      rows="1"></textarea>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.table.description') }}
                            *:</label>
                        <div class="col-sm-10">
                            <textarea name="risk_description" id="risk_description" class="form-control form-control-sm"
                                      rows="2" required></textarea>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.activity') }}
                            :</label>
                        <div class="col-sm-10">
                            <select name="risk_task" id="risk_task" class="form-select form-select-sm">
                                <option value="0">-- Selecione --</option>
                                @foreach($tasks as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.period') }}
                            :</label>
                        <div class="col-sm-10 d-flex gap-2 align-items-center">
                            <input type="date" name="risk_period_start_date" id="risk_period_start_date"
                                   class="form-control form-control-sm" style="width: 150px;">
                            <span>a</span>
                            <input type="date" name="risk_period_end_date" id="risk_period_end_date"
                                   class="form-control form-control-sm" style="width: 150px;">
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.ear_classification') }}
                            :</label>
                        <div class="col-sm-4">
                            <select name="risk_ear_classification" id="risk_ear_classification"
                                    class="form-select form-select-sm">
                                @foreach(__('planning/view.risks.ear') as $key => $label)
                                    <option value="{{ $loop->iteration }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.notes') }}
                            :</label>
                        <div class="col-sm-4">
                            <input type="text" name="risk_notes" id="risk_notes" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.potential') }}
                            :</label>
                        <div class="col-sm-10">
                            <select name="risk_potential_other_projects" id="risk_potential_other_projects"
                                    class="form-select form-select-sm" style="width: 100px;">
                                <option value="1">{{ __('planning/view.risks.form.yes') }}</option>
                                <option value="0">{{ __('planning/view.risks.form.no') }}</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="border-bottom border-secondary pb-1 mb-3 mt-4 fw-bold text-uppercase text-secondary"
                        style="font-size: 0.8rem;">
                        {{ __('planning/view.risks.form.section_analysis') }}
                    </h6>

                    <div class="row">
                        <div class="col-md-6 offset-md-2">
                            <div class="mb-2 row">
                                <label
                                    class="col-sm-4 col-form-label text-end small fw-bold">{{ __('planning/view.risks.table.probability') }}
                                    :</label>
                                <div class="col-sm-8">
                                    <select name="risk_probability" id="risk_probability"
                                            class="form-select form-select-sm" onchange="calculateExposure()">
                                        <option value="1">{{ __('planning/view.risks.levels.low') }}</option>
                                        <option value="2">{{ __('planning/view.risks.levels.medium') }}</option>
                                        <option value="3">{{ __('planning/view.risks.levels.high') }}</option>
                                        <option value="4">{{ __('planning/view.risks.levels.very_high') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <label
                                    class="col-sm-4 col-form-label text-end small fw-bold">{{ __('planning/view.risks.table.impact') }}
                                    :</label>
                                <div class="col-sm-8">
                                    <select name="risk_impact" id="risk_impact" class="form-select form-select-sm"
                                            onchange="calculateExposure()">
                                        <option value="1">{{ __('planning/view.risks.levels.low') }}</option>
                                        <option value="2">{{ __('planning/view.risks.levels.medium') }}</option>
                                        <option value="3">{{ __('planning/view.risks.levels.high') }}</option>
                                        <option value="4">{{ __('planning/view.risks.levels.very_high') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <label
                                    class="col-sm-4 col-form-label text-end small fw-bold">{{ __('planning/view.risks.table.exposure_factor') }}
                                    :</label>
                                <div class="col-sm-8">
                                    <input type="text" id="calculated_exposure"
                                           class="form-control form-control-sm fw-bold" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SEÇÃO 3 --}}
                    <h6 class="border-bottom border-secondary pb-1 mb-3 mt-4 fw-bold text-uppercase text-secondary"
                        style="font-size: 0.8rem;">
                        {{ __('planning/view.risks.form.section_response') }}
                    </h6>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.table.strategy') }}
                            :</label>
                        <div class="col-sm-4">
                            <select name="risk_strategy" id="risk_strategy" class="form-select form-select-sm">
                                <option value="0">{{ __('planning/view.risks.strategy.accept') }}</option>
                                <option value="1">{{ __('planning/view.risks.strategy.avoid') }}</option>
                                <option value="2">{{ __('planning/view.risks.strategy.mitigate') }}</option>
                                <option value="3">{{ __('planning/view.risks.strategy.transfer') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.prevention') }}
                            :</label>
                        <div class="col-sm-10">
                            <textarea name="risk_prevention_actions" id="risk_prevention_actions"
                                      class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.contingency_reserve') }}</label>
                        <div class="col-sm-10 pt-1">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="risk_is_contingency" id="reserve_yes"
                                       value="1">
                                <label class="form-check-label small"
                                       for="reserve_yes">{{ __('planning/view.risks.form.yes') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="risk_is_contingency" id="reserve_no"
                                       value="0">
                                <label class="form-check-label small"
                                       for="reserve_no">{{ __('planning/view.risks.form.no') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.contingency_plan') }}
                            :</label>
                        <div class="col-sm-10">
                            <textarea name="risk_contingency_plan" id="risk_contingency_plan"
                                      class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.trigger') }}
                            :</label>
                        <div class="col-sm-10">
                            <input type="text" name="risk_triggers" id="risk_triggers"
                                   class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.responsible') }}
                            :</label>
                        <div class="col-sm-4">
                            <select name="risk_responsible" id="risk_responsible" class="form-select form-select-sm">
                                <option value="0">-- Selecione --</option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- SEÇÃO 4 --}}
                    <h6 class="border-bottom border-secondary pb-1 mb-3 mt-4 fw-bold text-uppercase text-secondary"
                        style="font-size: 0.8rem;">
                        {{ __('planning/view.risks.form.section_control') }}
                    </h6>

                    <div class="mb-2 row">
                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.table.status') }}
                            :</label>
                        <div class="col-sm-4">
                            <select name="risk_status" id="risk_status" class="form-select form-select-sm">
                                <option value="0">{{ __('planning/view.risks.status.identified') }}</option>
                                <option value="1">{{ __('planning/view.risks.status.monitored') }}</option>
                                <option value="2">{{ __('planning/view.risks.status.occurred') }}</option>
                                <option value="3">{{ __('planning/view.risks.status.closed') }}</option>
                            </select>
                        </div>

                        <label
                            class="col-sm-2 col-form-label text-end small fw-bold">{{ __('planning/view.risks.form.active') }}</label>
                        <div class="col-sm-4">
                            <select name="risk_active" id="risk_active" class="form-select form-select-sm">
                                <option value="0">{{ __('planning/view.risks.form.yes') }}</option>
                                <option value="1">{{ __('planning/view.risks.form.no') }}</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary">Sallet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewRiskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title"><i class="bi bi-journal-text me-2"></i>Detalhes do Risco</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <h6 class="border-bottom border-secondary pb-1 mb-3 fw-bold text-uppercase text-secondary"
                    style="font-size: 0.8rem;">
                    {{ __('planning/view.risks.form.section_id') }}
                </h6>

                <dl class="row mb-0">
                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.table.title') }}:</dt>
                    <dd class="col-sm-10 fw-bold" id="view_risk_name"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.cause') }}:</dt>
                    <dd class="col-sm-10" id="view_risk_cause"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.consequence') }}:
                    </dt>
                    <dd class="col-sm-10" id="view_risk_consequence"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.table.description') }}:
                    </dt>
                    <dd class="col-sm-10 text-break" id="view_risk_description"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.activity') }}:</dt>
                    <dd class="col-sm-10" id="view_risk_task"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.period') }}:</dt>
                    <dd class="col-sm-10" id="view_risk_period"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.ear_classification') }}
                        :
                    </dt>
                    <dd class="col-sm-4" id="view_risk_ear"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.notes') }}:</dt>
                    <dd class="col-sm-4" id="view_risk_notes"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.potential') }}:</dt>
                    <dd class="col-sm-10" id="view_risk_potential"></dd>
                </dl>

                <h6 class="border-bottom border-secondary pb-1 mb-3 mt-4 fw-bold text-uppercase text-secondary"
                    style="font-size: 0.8rem;">
                    {{ __('planning/view.risks.form.section_analysis') }}
                </h6>

                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="table-light">
                            <tr>
                                <th>{{ __('planning/view.risks.table.probability') }}</th>
                                <th>{{ __('planning/view.risks.table.impact') }}</th>
                                <th>{{ __('planning/view.risks.table.exposure_factor') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td id="view_risk_probability"></td>
                                <td id="view_risk_impact"></td>
                                <td id="view_risk_exposure" class="fw-bold"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h6 class="border-bottom border-secondary pb-1 mb-3 mt-4 fw-bold text-uppercase text-secondary"
                    style="font-size: 0.8rem;">
                    {{ __('planning/view.risks.form.section_response') }}
                </h6>

                <dl class="row mb-0">
                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.table.strategy') }}:</dt>
                    <dd class="col-sm-10" id="view_risk_strategy"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.prevention') }}:</dt>
                    <dd class="col-sm-10" id="view_risk_prevention"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.contingency_reserve') }}
                        :
                    </dt>
                    <dd class="col-sm-10" id="view_risk_reserve"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.contingency_plan') }}
                        :
                    </dt>
                    <dd class="col-sm-10" id="view_risk_contingency_plan"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.trigger') }}:</dt>
                    <dd class="col-sm-10" id="view_risk_trigger"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.responsible') }}:
                    </dt>
                    <dd class="col-sm-10" id="view_risk_responsible"></dd>
                </dl>

                <h6 class="border-bottom border-secondary pb-1 mb-3 mt-4 fw-bold text-uppercase text-secondary"
                    style="font-size: 0.8rem;">
                    {{ __('planning/view.risks.form.section_control') }}
                </h6>

                <dl class="row mb-0">
                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.table.status') }}:</dt>
                    <dd class="col-sm-4" id="view_risk_status"></dd>

                    <dt class="col-sm-2 text-end text-muted small">{{ __('planning/view.risks.form.active') }}:</dt>
                    <dd class="col-sm-4" id="view_risk_active"></dd>
                </dl>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="managementPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="managementPlanContent">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="checklistModalContainer"></div>
<div id="watchListModalContainer"></div>
<div id="shortTermModalContainer"></div>
<div id="lessonsLearnedModalContainer"></div>
<div id="responseListModalContainer"></div>

<script>
    let mapLevels = @json(__('planning/view.risks.levels'), JSON_THROW_ON_ERROR);
    let mapStatus = @json(__('planning/view.risks.status'), JSON_THROW_ON_ERROR);
    let mapStrategy = @json(__('planning/view.risks.strategy'), JSON_THROW_ON_ERROR);
    let mapEar = @json(__('planning/view.risks.ear'), JSON_THROW_ON_ERROR);
    let mapYesNo = {
        1: "{{ __('planning/view.risks.form.yes') }}",
        0: "{{ __('planning/view.risks.form.no') }}"
    };

    let keyMapLevels = {1: 'low', 2: 'medium', 3: 'high', 4: 'very_high'};
    let keyMapStatus = {0: 'identified', 1: 'monitored', 2: 'occurred', 3: 'closed'};
    let keyMapStrategy = {0: 'accept', 1: 'avoid', 2: 'mitigate', 3: 'transfer'};
    let keyMapEar = {1: 'organizational', 2: 'technical', 3: 'external', 4: 'pm'};

    let listTasks = @json($tasks, JSON_THROW_ON_ERROR);
    let listUsers = @json($users, JSON_THROW_ON_ERROR);

    let exposureLevels = {
        'low': '{{ __('planning/view.risks.levels.low') }}',
        'medium': '{{ __('planning/view.risks.levels.medium') }}',
        'high': '{{ __('planning/view.risks.levels.high') }}',
        'very_high': '{{ __('planning/view.risks.levels.very_high') }}'
    };

    function openManagementPlanModal() {
        let modalEl = document.getElementById('managementPlanModal');
        let modal = new bootstrap.Modal(modalEl);
        modal.show();

        const url = "{{ route('projects.risks.plan.edit', $project->project_id) }}";

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(html => {
                document.getElementById('managementPlanContent').innerHTML = html;
            })
            .catch(error => {
                console.error('Erro:', error);
                document.getElementById('managementPlanContent').innerHTML =
                    '<div class="modal-header"><h5 class="modal-title">Erro</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>' +
                    '<div class="modal-body text-danger text-center">Erro ao carregar o plano de gerenciamento. Verifique o console.</div>';
            });
    }

    function openViewRiskModal(risk) {
        setText('view_risk_name', risk.risk_name);
        setText('view_risk_cause', risk.risk_cause);
        setText('view_risk_consequence', risk.risk_consequence);
        setText('view_risk_description', risk.risk_description);

        setText('view_risk_task', listTasks[risk.risk_task] || '-');
        setText('view_risk_responsible', listUsers[risk.risk_responsible] || '-');

        let start = risk.risk_period_start_date ? formatDate(risk.risk_period_start_date) : '...';
        let end = risk.risk_period_end_date ? formatDate(risk.risk_period_end_date) : '...';
        setText('view_risk_period', `${start} a ${end}`);

        let earKey = keyMapEar[risk.risk_ear_classification] || 'organizational';
        setText('view_risk_ear', mapEar[earKey] || '-');
        setText('view_risk_notes', risk.risk_notes);
        setText('view_risk_potential', mapYesNo[risk.risk_potential_other_projects] || mapYesNo[0]);

        let probKey = keyMapLevels[risk.risk_probability] || 'low';
        let impKey = keyMapLevels[risk.risk_impact] || 'low';

        setText('view_risk_probability', mapLevels[probKey]);
        setText('view_risk_impact', mapLevels[impKey]);

        const p = risk.risk_probability || 1;
        const i = risk.risk_impact || 1;
        const score = p * i;
        let expLabel = mapLevels.low;
        let expClass = 'bg-success text-white';

        if (score >= 12) {
            expLabel = mapLevels.very_high;
            expClass = 'bg-danger text-white';
        } else if (score >= 6) {
            expLabel = mapLevels.high;
            expClass = 'bg-danger text-white';
        } else if (score >= 3) {
            expLabel = mapLevels.medium;
            expClass = 'bg-warning text-dark';
        }

        const expEl = document.getElementById('view_risk_exposure');
        expEl.innerText = expLabel;
        expEl.className = `fw-bold p-1 rounded ${expClass}`;

        let stratKey = keyMapStrategy[risk.risk_strategy] || 'accept';
        setText('view_risk_strategy', mapStrategy[stratKey]);
        setText('view_risk_prevention', risk.risk_prevention_actions);
        setText('view_risk_reserve', mapYesNo[risk.risk_is_contingency] || mapYesNo[0]);
        setText('view_risk_contingency_plan', risk.risk_contingency_plan);
        setText('view_risk_trigger', risk.risk_triggers);

        let statusKey = keyMapStatus[risk.risk_status] || 'identified';
        setText('view_risk_status', mapStatus[statusKey]);

        let activeText = (risk.risk_active === 0) ? mapYesNo[1] : mapYesNo[0];
        setText('view_risk_active', activeText);

        const modal = new bootstrap.Modal(document.getElementById('viewRiskModal'));
        modal.show();
    }

    function openEditRiskModal(risk) {
        document.getElementById('risk_name').value = risk.risk_name;
        document.getElementById('risk_cause').value = risk.risk_cause || '';
        document.getElementById('risk_consequence').value = risk.risk_consequence || '';
        document.getElementById('risk_description').value = risk.risk_description || '';

        document.getElementById('risk_task').value = risk.risk_task || 0;
        document.getElementById('risk_period_start_date').value = risk.risk_period_start_date ? risk.risk_period_start_date.substring(0, 10) : '';
        document.getElementById('risk_period_end_date').value = risk.risk_period_end_date ? risk.risk_period_end_date.substring(0, 10) : '';

        document.getElementById('risk_ear_classification').value = risk.risk_ear_classification || 1;
        document.getElementById('risk_notes').value = risk.risk_notes || '';
        document.getElementById('risk_potential_other_projects').value = risk.risk_potential_other_projects || 0;

        document.getElementById('risk_probability').value = risk.risk_probability || 1;
        document.getElementById('risk_impact').value = risk.risk_impact || 1;

        document.getElementById('risk_strategy').value = risk.risk_strategy || 0;
        document.getElementById('risk_prevention_actions').value = risk.risk_prevention_actions || '';
        document.getElementById('risk_contingency_plan').value = risk.risk_contingency_plan || '';
        document.getElementById('risk_triggers').value = risk.risk_triggers || '';

        if (risk.risk_is_contingency === 1) document.getElementById('reserve_yes').checked = true;
        else document.getElementById('reserve_no').checked = true;

        document.getElementById('risk_responsible').value = risk.risk_responsible || 0;
        document.getElementById('risk_status').value = risk.risk_status || 0;
        document.getElementById('risk_active').value = risk.risk_active;

        const form = document.getElementById('riskForm');
        let url = "{{ route('projects.risks.update', ['project' => $project->project_id, 'risk' => ':ID']) }}";
        form.action = url.replace(':ID', risk.risk_id);

        document.getElementById('method_field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('riskModalTitle').innerText = 'Editar Risco';

        calculateExposure();

        const modal = new bootstrap.Modal(document.getElementById('riskModal'));
        modal.show();
    }

    function openNewRiskModal() {
        document.getElementById('riskForm').reset();
        document.getElementById('method_field').innerHTML = '';
        document.getElementById('riskForm').action = "{{ route('projects.risks.store', $project->project_id) }}";
        document.getElementById('riskModalTitle').innerText = 'Novo Risco';

        calculateExposure();

        const modal = new bootstrap.Modal(document.getElementById('riskModal'));
        modal.show();
    }

    function calculateExposure() {
        const p = parseInt(document.getElementById('risk_probability').value) || 1;
        const i = parseInt(document.getElementById('risk_impact').value) || 1;
        const score = p * i;

        let label = exposureLevels.low;
        let colorClass = 'text-success';

        if (score >= 12) {
            label = exposureLevels.very_high;
            colorClass = 'text-danger';
        } else if (score >= 6) {
            label = exposureLevels.high;
            colorClass = 'text-danger';
        } else if (score >= 3) {
            label = exposureLevels.medium;
            colorClass = 'text-warning';
        }

        const input = document.getElementById('calculated_exposure');
        input.value = label;
        input.className = `form-control form-control-sm fw-bold ${colorClass}`;
    }

    function setText(id, value) {
        document.getElementById(id).innerText = value || '-';
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        if (dateStr.length > 10) dateStr = dateStr.substring(0, 10);
        const parts = dateStr.split('-');
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }

    function openChecklistModal() {
        const url = "{{ route('projects.risks.checklist', $project->project_id) }}";

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const container = document.getElementById('checklistModalContainer');
                container.innerHTML = html;

                let modalEl = container.querySelector('.modal');
                if (!modalEl) {
                    container.innerHTML = `
                    <div class="modal fade" id="dynamicChecklistModal" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                ${html}
                            </div>
                        </div>
                    </div>
                `;
                    modalEl = document.getElementById('dynamicChecklistModal');
                }

                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            })
            .catch(error => console.error('Erro ao carregar checklist:', error));
    }

    function openWatchListModal() {
        const url = "{{ route('projects.risks.watchlist', $project->project_id) }}";

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const container = document.getElementById('watchListModalContainer');
                container.innerHTML = html;

                let modalEl = container.querySelector('.modal');
                if (!modalEl) {
                    container.innerHTML = `
                    <div class="modal fade" id="dynamicWatchListModal" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                ${html}
                            </div>
                        </div>
                    </div>
                `;
                    modalEl = document.getElementById('dynamicWatchListModal');
                }

                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            })
            .catch(error => console.error('Erro ao carregar Watch List:', error));
    }

    function openShortTermModal() {
        const url = "{{ route('projects.risks.short_term', $project->project_id) }}";

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const container = document.getElementById('shortTermModalContainer');
                container.innerHTML = html;

                let modalEl = container.querySelector('.modal');
                if (!modalEl) {
                    container.innerHTML = `
                    <div class="modal fade" id="dynamicShortTermModal" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                ${html}
                            </div>
                        </div>
                    </div>
                `;
                    modalEl = document.getElementById('dynamicShortTermModal');
                }

                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            })
            .catch(error => console.error('Erro ao carregar Short Term List:', error));
    }

    function openLessonsLearnedModal() {
        const url = "{{ route('projects.risks.lessons_learned', $project->project_id) }}";

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const container = document.getElementById('lessonsLearnedModalContainer');
                container.innerHTML = html;

                let modalEl = container.querySelector('.modal');
                if (!modalEl) {
                    container.innerHTML = `
                    <div class="modal fade" id="dynamicLessonsLearnedModal" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                ${html}
                            </div>
                        </div>
                    </div>
                `;
                    modalEl = document.getElementById('dynamicLessonsLearnedModal');
                }

                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            })
            .catch(error => console.error('Erro ao carregar Lessons Learned:', error));
    }

    function openResponseListModal() {
        const url = "{{ route('projects.risks.response_list', $project->project_id) }}";

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const container = document.getElementById('responseListModalContainer');
                container.innerHTML = html;

                let modalEl = container.querySelector('.modal');
                if (!modalEl) {
                    // Usamos modal-xl para caber todas as colunas
                    container.innerHTML = `
                    <div class="modal fade" id="dynamicResponseListModal" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                ${html}
                            </div>
                        </div>
                    </div>
                `;
                    modalEl = document.getElementById('dynamicResponseListModal');
                }

                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            })
            .catch(error => console.error('Erro ao carregar Response List:', error));
    }
</script>
