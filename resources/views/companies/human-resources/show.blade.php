@php use Carbon\Carbon; @endphp
@extends('dashboard')

@section('title', __('companies/view.hr.details.title'))

@php
    $companyProjects = \Illuminate\Support\Facades\DB::table('dotp_projects')
                        ->where('project_company', $company->company_id)
                        ->orderBy('project_name')
                        ->get();
@endphp

@section('dashboard-content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center p-3">
            <h5 class="mb-0 fw-bold text-dark">{{ __('companies/view.hr.details.title') }}</h5>

            @if(!$hr->can_delete)
                <span class="text-success small fw-bold">
                    {{ __('companies/view.hr.messages.cannot_delete_desc') }} <i class="bi bi-x-circle text-danger ms-1"
                                                                                 title="{{ __('companies/view.hr.details.cannot_delete_title') }}"></i>
                </span>
            @endif
        </div>

        <div class="card-body p-4">
            <h6 class="text-warning fw-bold mb-3">{{ __('companies/view.hr.details.section_details') }}</h6>
            <div class="row mb-4 text-sm">
                <div class="col-md-2 text-end text-muted fw-bold">{{ __('companies/view.hr.details.member') }}:</div>
                <div class="col-md-10 fw-bold">
                    {{ $hr->user->contact->full_name ?? '--' }}
                    <a href="#"
                       class="text-decoration-none ms-2 small fw-normal">({{ __('companies/view.hr.details.access_contact') }}
                        )</a>
                </div>

                <div class="col-md-2 text-end text-muted fw-bold mt-2">{{ __('companies/view.hr.details.roles') }}:
                </div>
                <div class="col-md-10 mt-2">
                    {{ $hr->roles->pluck('human_resources_role_name')->implode(', ') ?: '--' }}
                </div>

                <div class="col-md-2 text-end text-muted fw-bold mt-2">{{ __('companies/view.hr.details.curriculum') }}
                    :
                </div>
                <div class="col-md-10 mt-2" id="display_lattes">
                    @if($hr->human_resource_lattes_url)
                        <a href="{{ $hr->human_resource_lattes_url }}"
                           target="_blank">{{ $hr->human_resource_lattes_url }}</a>
                    @else
                        --
                    @endif
                </div>
            </div>

            <h6 class="text-warning fw-bold mb-3">{{ __('companies/view.hr.details.section_hours') }}</h6>
            <div class="row mb-4 text-sm">
                @php
                    $days = [
                        'sun' => __('companies/view.hr.days.sun'),
                        'mon' => __('companies/view.hr.days.mon'),
                        'tue' => __('companies/view.hr.days.tue'),
                        'wed' => __('companies/view.hr.days.wed'),
                        'thu' => __('companies/view.hr.days.thu'),
                        'fri' => __('companies/view.hr.days.fri'),
                        'sat' => __('companies/view.hr.days.sat'),
                    ];
                @endphp

                @foreach($days as $key => $label)
                    <div class="col-md-2 text-end text-muted fw-bold mt-1">{{ $label }}:</div>
                    <div class="col-md-10 mt-1 fw-bold" id="display_{{ $key }}">
                        {{ $hr->{"human_resource_$key"} ?? 0 }}
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('companies.show', $company->company_id) }}" class="btn btn-outline-secondary btn-sm">
                    {{ __('companies/view.hr.actions.cancel') }}
                </a>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#editHrModal">
                    {{ __('companies/view.hr.actions.edit') }}
                </button>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4 text-center">
            <h6 class="text-dark fw-normal mb-3">{{ __('companies/view.hr.details.section_costs') }}</h6>

            <table class="table table-bordered table-sm align-middle mx-auto" style="max-width: 900px;" id="costsTable">
                <thead style="background-color: #FFC107;">
                <tr class="small fw-bold text-dark">
                    <th>{{ __('companies/view.hr.costs.project') }}</th>
                    <th>{{ __('companies/view.hr.costs.start_date') }}</th>
                    <th>{{ __('companies/view.hr.costs.end_date') }}</th>
                    <th>{{ __('companies/view.hr.costs.standard_rate') }}</th>
                    <th style="width: 80px;"></th>
                </tr>
                </thead>
                <tbody id="costsTableBody">
                @if($hr->user && $hr->user->costs)
                    @foreach($hr->user->costs as $cost)
                        @php
                            $projName = $companyProjects->firstWhere('project_id', $cost->cost_project_id)->project_name ?? __('companies/view.hr.costs.not_available');
                        @endphp
                        <tr id="cost-row-{{ $cost->cost_id }}">
                            <td class="small fw-bold">{{ $projName }}</td>
                            <td class="small">{{ $cost->cost_date_begin ? Carbon::parse($cost->cost_date_begin)->format('d/m/Y') : '--' }}</td>
                            <td class="small">{{ $cost->cost_date_end ? Carbon::parse($cost->cost_date_end)->format('d/m/Y') : '--' }}</td>
                            <td class="small">{{ number_format($cost->cost_value_unitary, 2, ',', '.') }}</td>
                            <td>
                                <button type="button" class="btn btn-xs btn-link text-dark p-0"
                                        onclick="editCost({{ $cost->cost_id }}, {{ $cost->cost_project_id }}, '{{ $cost->cost_date_begin ? Carbon::parse($cost->cost_date_begin)->format('Y-m-d') : '' }}', '{{ $cost->cost_date_end ? Carbon::parse($cost->cost_date_end)->format('Y-m-d') : '' }}', '{{ $cost->cost_value_unitary }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-link text-danger p-0 ms-1"
                                        onclick="deleteCost({{ $cost->cost_id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                <tfoot class="bg-light">
                <tr>
                    <td>
                        <select id="new_cost_project_id" class="form-select form-select-sm">
                            <option value="">{{ __('companies/view.hr.actions.select') }}</option>
                            @foreach($companyProjects as $proj)
                                <option value="{{ $proj->project_id }}">{{ $proj->project_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="date" id="new_cost_start" class="form-control form-control-sm"></td>
                    <td><input type="date" id="new_cost_end" class="form-control form-control-sm"></td>
                    <td><input type="number" step="0.01" id="new_cost_value" class="form-control form-control-sm"
                               placeholder="0.00"></td>
                    <td>
                        <button type="button" class="btn btn-xs btn-outline-secondary w-100" onclick="saveNewCost()">
                            {{ __('companies/view.hr.actions.save') }}
                        </button>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="modal fade" id="editHrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formEditHr" onsubmit="updateHumanResource(event)">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title h6 fw-bold">{{ __('companies/view.hr.actions.edit') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label
                                class="form-label fw-bold small">{{ __('companies/view.hr.details.curriculum') }}</label>
                            <input type="url" name="human_resource_lattes_url" class="form-control form-control-sm"
                                   value="{{ $hr->human_resource_lattes_url }}">
                        </div>

                        <h6 class="fw-bold small border-bottom pb-1 mt-4">{{ __('companies/view.hr.details.section_hours') }}</h6>
                        <div class="row g-2">
                            @foreach($days as $key => $label)
                                <div class="col-md-6 d-flex align-items-center">
                                    <label class="small text-muted me-2 text-end" style="width: 80px;">{{ $label }}
                                        :</label>
                                    <input type="number" name="human_resource_{{ $key }}"
                                           class="form-control form-control-sm" min="0" max="24"
                                           value="{{ $hr->{"human_resource_key"} ?? 0 }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer bg-light justify-content-center">
                        <button type="submit" id="btnUpdateHr"
                                class="btn btn-primary btn-sm px-4">{{ __('companies/view.hr.actions.save_changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editCostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">{{ __('companies/view.hr.actions.edit') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_cost_id">
                    <div class="mb-2">
                        <label class="small fw-bold">{{ __('companies/view.hr.costs.project') }}</label>
                        <select id="edit_cost_project_id" class="form-select form-select-sm">
                            <option value="">{{ __('companies/view.hr.actions.select') }}</option>
                            @foreach($companyProjects as $proj)
                                <option value="{{ $proj->project_id }}">{{ $proj->project_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold">{{ __('companies/view.hr.costs.start_date') }}</label>
                        <input type="date" id="edit_cost_start" class="form-control form-control-sm">
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold">{{ __('companies/view.hr.costs.end_date') }}</label>
                        <input type="date" id="edit_cost_end" class="form-control form-control-sm">
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold">{{ __('companies/view.hr.costs.standard_rate') }}</label>
                        <input type="number" step="0.01" id="edit_cost_value" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="modal-footer bg-light p-1 justify-content-center">
                    <button type="button" class="btn btn-primary btn-sm w-100" onclick="updateCost()">
                        {{ __('companies/view.hr.actions.save_changes') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteCostConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title h6 fw-bold">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('companies/view.hr.messages.error_title') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('companies/view.hr.messages.confirm_delete_cost') }}
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        {{ __('companies/view.hr.actions.cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="executeDeleteCost()">
                        {{ __('companies/view.hr.table.actions') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center p-3">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bi bi-radar me-2"></i>{{ __('companies/view.hr.skills.title') }}
            </h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSkillModal">
                <i class="bi bi-plus-lg"></i> {{ __('companies/view.hr.skills.add_btn') }}
            </button>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-5 d-flex align-items-center justify-content-center">
                    <div style="width: 100%; max-width: 350px;">
                        <canvas id="skillsRadarChart"></canvas>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>{{ __('companies/view.hr.skills.skill') }}</th>
                                <th>{{ __('companies/view.hr.skills.type') }}</th>
                                <th>{{ __('companies/view.hr.skills.level') }}</th>
                                <th class="text-end">{{ __('companies/view.hr.skills.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($hr->skills as $skill)
                                <tr id="skill-row-{{ $skill->skill_id }}">
                                    <td class="fw-bold">{{ $skill->skill_name }}</td>
                                    <td>
                                        @if($skill->skill_type === 'technical')
                                            <span class="badge bg-info text-dark">{{ __('companies/view.hr.skills.technical') }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ __('companies/view.hr.skills.behavioral') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star{{ $i <= $skill->pivot->proficiency_level ? '-fill text-warning' : ' text-muted opacity-25' }}"></i>
                                        @endfor
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-xs text-danger"
                                                onclick="deleteSkill({{ $skill->skill_id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted small py-3">
                                        {{ __('companies/view.hr.skills.empty') }}
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSkillModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                    <form onsubmit="saveSkill(event)">
                        <div class="modal-content">
                            <div class="modal-header bg-light">
                                <h6 class="modal-title fw-bold">{{ __('companies/view.hr.skills.new_skill') }}</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">{{ __('companies/view.hr.skills.skill_name') }}</label>
                                    <input type="text" id="new_skill_name" class="form-control"
                                           placeholder="{{ __('companies/view.hr.skills.skill_placeholder') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">{{ __('companies/view.hr.skills.type') }}</label>
                                    <select id="new_skill_type" class="form-select">
                                        <option value="technical">{{ __('companies/view.hr.skills.technical_hard') }}</option>
                                        <option value="behavioral">{{ __('companies/view.hr.skills.behavioral_soft') }}</option>
                                    </select>
                                </div>
                                <div class="mb-3 mt-4">
                                    <label class="form-label small fw-bold">{{ __('companies/view.hr.skills.proficiency_level') }}</label>
                                    <div class="d-flex justify-content-between px-1">
                                        <small class="text-muted fw-bold">{{ __('companies/view.hr.skills.beginner') }}</small>
                                        <small class="text-muted fw-bold">{{ __('companies/view.hr.skills.expert') }}</small>
                                    </div>
                                    <input type="range" id="new_skill_level" class="form-range mt-2" min="1" max="5" step="1" value="3">
                                </div>
                            </div>
                            <div class="modal-footer bg-light justify-content-center p-3">
                                <button type="submit" id="btnSaveSkill" class="btn btn-primary px-5">{{ __('companies/view.hr.actions.save') }}</button>
                            </div>
                        </div>
                    </form>
        </div>
    </div>

    <div class="modal fade" id="deleteSkillConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title h6 fw-bold">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('companies/view.hr.messages.error_title') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('companies/view.hr.messages.confirm_delete_skill') }}
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        {{ __('companies/view.hr.actions.cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="executeDeleteSkill()">
                        {{ __('companies/view.hr.table.actions') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @includeIf('components.status_modal')

    <script>
        let editHrModal;
        let editCostModalInstance;
        let deleteCostConfirmModalInstance;
        let deleteSkillConfirmModalInstance;

        let costIdToDelete = null;
        let skillIdToDelete = null;

        const userId = {{ $hr->human_resource_user_id ?? 'null' }};

        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById('editHrModal')) {
                editHrModal = new bootstrap.Modal(document.getElementById('editHrModal'));
            }
            if (document.getElementById('editCostModal')) {
                editCostModalInstance = new bootstrap.Modal(document.getElementById('editCostModal'));
            }
            if (document.getElementById('deleteCostConfirmModal')) {
                deleteCostConfirmModalInstance = new bootstrap.Modal(document.getElementById('deleteCostConfirmModal'));
            }
            if (document.getElementById('deleteSkillConfirmModal')) {
                deleteSkillConfirmModalInstance = new bootstrap.Modal(document.getElementById('deleteSkillConfirmModal'));
            }
        });

        function updateHumanResource(event) {
            event.preventDefault();

            const form = document.getElementById('formEditHr');
            const btn = document.getElementById('btnUpdateHr');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            const formData = new FormData(form);
            formData.append('_method', 'PUT');

            fetch("{{ route('companies.hr.update', ['company' => $company->company_id, 'hr_id' => $hr->human_resource_id]) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                }
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw data;
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        editHrModal.hide();

                        const hrData = data.data;
                        const lattesEl = document.getElementById('display_lattes');
                        if (hrData.human_resource_lattes_url) {
                            lattesEl.innerHTML = `<a href="${hrData.human_resource_lattes_url}" target="_blank">${hrData.human_resource_lattes_url}</a>`;
                        } else {
                            lattesEl.innerHTML = '--';
                        }

                        ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'].forEach(day => {
                            document.getElementById('display_' + day).innerText = hrData['human_resource_' + day] || 0;
                        });

                        if (typeof showMessage === 'function') {
                            showMessage("{{ __('companies/view.hr.messages.success_title') }}", data.message, 'success');
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    if (typeof showMessage === 'function') {
                        showMessage("{{ __('companies/view.hr.messages.error_title') }}", err.message || "{{ __('companies/view.hr.messages.save_error') }}", 'error');
                    }
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        }

        function saveNewCost() {
            if (!userId) {
                if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.error_title') }}", "{{ __('companies/view.hr.messages.save_error') }}", 'error');
                return;
            }

            const projectId = document.getElementById('new_cost_project_id').value;
            const start = document.getElementById('new_cost_start').value;
            const end = document.getElementById('new_cost_end').value;
            const value = document.getElementById('new_cost_value').value;

            if (!projectId || !start || !value) {
                if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.error_title') }}", "{{ __('companies/view.hr.messages.save_error') }}", 'error');
                return;
            }

            const formData = new FormData();
            formData.append('cost_project_id', projectId);
            formData.append('cost_date_begin', start);
            if (end) formData.append('cost_date_end', end);
            formData.append('cost_value', value);

            fetch("{{ route('users.costs.store', ':user_id') }}".replace(':user_id', userId), {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                }
            })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw data;
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.success_title') }}", data.message, 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    }
                })
                .catch(err => {
                    if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.error_title') }}", err.message || "{{ __('companies/view.hr.messages.save_error') }}", 'error');
                });
        }

        function editCost(id, projectId, start, end, value) {
            document.getElementById('edit_cost_id').value = id;
            document.getElementById('edit_cost_project_id').value = projectId;
            document.getElementById('edit_cost_start').value = start.split(' ')[0];
            document.getElementById('edit_cost_end').value = end ? end.split(' ')[0] : '';
            document.getElementById('edit_cost_value').value = parseFloat(value).toFixed(2);
            editCostModalInstance.show();
        }

        function updateCost() {
            const id = document.getElementById('edit_cost_id').value;
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('cost_project_id', document.getElementById('edit_cost_project_id').value);
            formData.append('cost_date_begin', document.getElementById('edit_cost_start').value);
            formData.append('cost_date_end', document.getElementById('edit_cost_end').value);
            formData.append('cost_value', document.getElementById('edit_cost_value').value);

            let url = "{{ route('users.costs.update', ['user' => ':user_id', 'cost' => ':id']) }}"
                .replace(':user_id', userId)
                .replace(':id', id);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                }
            })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw data;
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        editCostModalInstance.hide();
                        if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.success_title') }}", data.message, 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    }
                })
                .catch(err => {
                    if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.error_title') }}", err.message || "{{ __('companies/view.hr.messages.save_error') }}", 'error');
                });
        }

        function deleteCost(id) {
            costIdToDelete = id;
            deleteCostConfirmModalInstance.show();
        }

        function executeDeleteCost() {
            if (!costIdToDelete) return;

            let url = "{{ route('users.costs.destroy', ['user' => ':user_id', 'cost' => ':id']) }}"
                .replace(':user_id', userId)
                .replace(':id', costIdToDelete);

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        deleteCostConfirmModalInstance.hide();
                        document.getElementById('cost-row-' + costIdToDelete).remove();
                        if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.success_title') }}", data.message, 'success');
                        costIdToDelete = null;
                    }
                });
        }

        let skillModalInstance;

        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById('addSkillModal')) {
                skillModalInstance = new bootstrap.Modal(document.getElementById('addSkillModal'));
            }
            initRadarChart();
        });

        function initRadarChart() {
            const ctx = document.getElementById('skillsRadarChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: @json($hr->skills->pluck('skill_name'), JSON_THROW_ON_ERROR),
                    datasets: [{
                        label: '{{ __('companies/view.hr.skills.proficiency_level') }}',
                        data: @json($hr->skills->pluck('pivot.proficiency_level'), JSON_THROW_ON_ERROR),
                        fill: true,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgb(54, 162, 235)',
                        pointBackgroundColor: 'rgb(54, 162, 235)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(54, 162, 235)'
                    }]
                },
                options: {
                    elements: {line: {tension: 0.3}},
                    scales: {
                        r: {
                            angleLines: {display: true},
                            suggestedMin: 0,
                            suggestedMax: 5,
                            ticks: {stepSize: 1}
                        }
                    }
                }
            });
        }

        function saveSkill(event) {
            event.preventDefault();
            const btn = document.getElementById('btnSaveSkill');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '{{ __('companies/view.hr.actions.saving') }}';

            const formData = new FormData();
            formData.append('skill_name', document.getElementById('new_skill_name').value);
            formData.append('skill_type', document.getElementById('new_skill_type').value);
            formData.append('proficiency_level', document.getElementById('new_skill_level').value);

            fetch("{{ route('companies.hr.skills.store', ['company' => $company->company_id, 'hr_id' => $hr->human_resource_id]) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        skillModalInstance.hide();
                        window.location.reload();
                    } else {
                        if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.error_title') }}", data.message, 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.error_title') }}", err.message, 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        }

        function deleteSkill(skillId) {
            skillIdToDelete = skillId;
            if (deleteSkillConfirmModalInstance) {
                deleteSkillConfirmModalInstance.show();
            }
        }

        function executeDeleteSkill() {
            if (!skillIdToDelete) return;

            let url = "{{ route('companies.hr.skills.destroy', ['company' => $company->company_id, 'hr_id' => $hr->human_resource_id, 'skill_id' => ':id']) }}".replace(':id', skillIdToDelete);

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        deleteSkillConfirmModalInstance.hide();
                        document.getElementById('skill-row-' + skillIdToDelete).remove();

                        if (typeof showMessage === 'function') {
                            showMessage("{{ __('companies/view.hr.messages.success_title') }}", data.message, 'success');
                        }
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        if (typeof showMessage === 'function') {
                            showMessage("{{ __('companies/view.hr.messages.error_title') }}", data.message, 'error');
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    if (typeof showMessage === 'function') {
                        showMessage("{{ __('companies/view.hr.messages.error_title') }}", "Erro ao remover.", 'error');
                    }
                })
                .finally(() => {
                    skillIdToDelete = null;
                });
        }
    </script>
@endsection
