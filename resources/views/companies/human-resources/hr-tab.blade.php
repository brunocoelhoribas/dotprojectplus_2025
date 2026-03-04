@php
    use App\Models\HumanResource\HumanResource;
    use App\Models\User\User;
    use Illuminate\Support\Facades\DB;

    $users = User::whereHas('contact', static function ($query) use ($company) {
        $query->where('contact_company', $company->company_id);
    })->with(['contact', 'humanResource.roles'])->get();

    $existingHrUserIds = HumanResource::pluck('human_resource_user_id')->toArray();
    $availableUsers = $users->whereNotIn('user_id', $existingHrUserIds);
    $availableRoles = DB::table('dotp_human_resources_role')->orderBy('human_resources_role_name')->get();
@endphp

<div id="hr-content">
    <div class="d-flex justify-content-end gap-2 mb-3 mt-3">
        <button type="button" class="btn btn-outline-success btn-sm fw-bold" data-bs-toggle="modal"
            data-bs-target="#performanceMatrixModal">
            <i class="bi bi-bar-chart-line-fill me-1"></i>
            {{ __('companies/view.hr.performance.open_matrix_btn') ?? 'Abrir Matriz 9-Box' }}
        </button>

        <button type="button" class="btn btn-outline-primary btn-sm fw-bold" data-bs-toggle="modal"
            data-bs-target="#raciMatrixModal">
            <i class="bi bi-grid-3x3 me-1"></i>
            {{ __('companies/view.hr.raci.open_matrix_btn') ?? 'Abrir Matriz RACI' }}
        </button>

        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newHrModal">
            <i class="bi bi-plus-lg me-1"></i> {{ __('companies/view.hr.add_btn') }}
        </button>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-bordered table-hover align-middle mb-0" id="hrTable">
            <thead style="background-color: #e0e0e0;">
                <tr class="text-center small fw-bold">
                    <th style="width: 50px; background-color: #FFC107;"></th>
                    <th style="width: 35%; background-color: #FFC107;">{{ __('companies/view.hr.table.member') }}</th>
                    <th style="width: 45%; background-color: #FFC107;">{{ __('companies/view.hr.table.roles') }}</th>
                    <th style="background-color: #FFC107;">{{ __('companies/view.hr.table.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $hasHr = $user->humanResource !== null;
                        $hrId = $hasHr ? $user->humanResource->human_resource_id : null;
                        $canDelete = !$hasHr || $user->humanResource->can_delete;
                        $rowClass = $hasHr ? '' : 'table-danger';
                        $rolesText = '';
                        if ($hasHr && $user->humanResource->roles) {
                            $rolesText = $user->humanResource->roles->pluck('human_resources_role_name')->implode(', ');
                        }
                    @endphp
                    <tr class="{{ $rowClass }}" id="hr-row-{{ $hrId ?? 'u' . $user->user_id }}">
                        <td class="text-center">
                            @if($hasHr)
                                <a href="{{ route('companies.hr.show', ['company' => $company->company_id, 'hr_id' => $hrId]) }}"
                                    class="text-dark" title="{{ __('companies/view.hr.actions.edit') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            @else
                                <span class="text-muted opacity-25" title="{{ __('companies/view.hr.not_configured') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </span>
                            @endif
                        </td>
                        <td class="fw-bold text-dark">
                            {{ $user->contact->full_name ?? $user->user_username }}
                        </td>
                        <td class="small text-muted">{{ $rolesText ?: '--' }}</td>
                        <td class="text-center">
                            @if($hasHr)
                                @if($canDelete)
                                    <button type="button" class="btn btn-xs btn-outline-danger"
                                        onclick="deleteHumanResource({{ $hrId }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @else
                                    <span class="small text-success d-inline-block text-start lh-sm" style="font-size: 0.75rem;">
                                        {{ __('companies/view.hr.messages.cannot_delete_desc') }}
                                    </span>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">{{ __('companies/view.hr.table.empty') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- INCLUSÃO DO ARQUIVO DA MATRIZ RACI --}}
@include('companies.human-resources.raci-matrix')

{{-- INCLUSÃO DO ARQUIVO DA MATRIZ DE PERFORMANCE --}}
@include('companies.human-resources.performance-matrix')

<div class="modal fade" id="newHrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formNewHr" onsubmit="saveHumanResource(event)">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title h6 fw-bold">
                        <i class="bi bi-person-plus me-2"></i>{{ __('companies/view.hr.form.title') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4 bg-light p-2 rounded border">
                        <label
                            class="form-label fw-bold small mb-2 d-block">{{ __('companies/view.hr.form.creation_type') }}</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="creation_type" id="type_existing"
                                    value="existing" checked onchange="toggleHrCreationType()">
                                <label class="form-check-label small fw-bold" for="type_existing">
                                    {{ __('companies/view.hr.form.existing_user') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="creation_type" id="type_new"
                                    value="new" onchange="toggleHrCreationType()">
                                <label class="form-check-label small fw-bold" for="type_new">
                                    {{ __('companies/view.hr.form.new_user') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="div_existing_user" class="mb-3">
                        <label for="user_id" class="form-label fw-bold small">
                            {{ __('companies/view.hr.form.registered_user') }} <span class="text-danger">*</span>
                        </label>
                        <select name="user_id" id="user_id" class="form-select form-select-sm" required>
                            <option value="">{{ __('companies/view.hr.form.select_available_user') }}</option>
                            @forelse($availableUsers as $u)
                                <option value="{{ $u->user_id }}">
                                    {{ $u->contact->full_name ?? $u->user_username }}
                                </option>
                            @empty
                                <option value="" disabled>{{ __('companies/view.hr.form.all_users_configured') }}</option>
                            @endforelse
                        </select>
                    </div>

                    <div id="div_new_user" class="mb-3 d-none">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="first_name"
                                    class="form-label fw-bold small">{{ __('companies/view.hr.form.first_name') }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" id="first_name"
                                    class="form-control form-control-sm" maxlength="100">
                            </div>
                            <div class="col-md-6">
                                <label for="last_name"
                                    class="form-label fw-bold small">{{ __('companies/view.hr.form.last_name') }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" id="last_name" class="form-control form-control-sm"
                                    maxlength="100">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <label for="roles" class="form-label fw-bold small">
                            {{ __('companies/view.hr.details.roles') }}
                        </label>
                        <select name="roles[]" id="roles" class="form-select form-select-sm" multiple
                            style="height: 90px;">
                            @foreach($availableRoles as $role)
                                <option value="{{ $role->human_resources_role_id }}">{{ $role->human_resources_role_name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text small text-muted">{{ __('companies/view.hr.form.ctrl_multiple') }}</div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label class="form-label fw-bold small">{{ __('companies/view.hr.details.curriculum') }}</label>
                        <input type="url" name="human_resource_lattes_url" class="form-control form-control-sm"
                            placeholder="http://lattes.cnpq.br/...">
                    </div>

                    <h6 class="fw-bold small border-bottom pb-1 mt-4">
                        {{ __('companies/view.hr.details.section_hours') }}</h6>
                    <div class="row g-2">
                        @php
                            $days = [
                                'sun' => __('companies/view.hr.days.sun'),
                                'mon' => __('companies/view.hr.days.mon'),
                                'tue' => __('companies/view.hr.days.tue'),
                                'wed' => __('companies/view.hr.days.wed'),
                                'thu' => __('companies/view.hr.days.thu'),
                                'fri' => __('companies/view.hr.days.fri'),
                                'sat' => __('companies/view.hr.days.sat')
                            ];
                        @endphp

                        @foreach($days as $key => $label)
                            <div class="col-md-6 d-flex align-items-center">
                                <label class="small text-muted me-2 text-end" style="width: 80px;">{{ $label }}:</label>
                                <input type="number" name="human_resource_{{ $key }}" class="form-control form-control-sm"
                                    min="0" max="24" value="0">
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="modal-footer bg-light justify-content-center">
                    <button type="submit" id="btnSubmitHr" class="btn btn-primary btn-sm px-4">
                        {{ __('companies/view.hr.form.submit') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="deleteHrConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title h6 fw-bold">
                    <i
                        class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('companies/view.hr.messages.error_title') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ __('companies/view.hr.messages.confirm_delete_hr') }}
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    {{ __('companies/view.hr.actions.cancel') }}
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="executeDeleteHumanResource()">
                    {{ __('companies/view.hr.table.actions') }}
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="statusMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header text-white" id="statusModalHeader">
                <h5 class="modal-title h6 fw-bold" id="statusModalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4" id="statusModalBody">
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    let raciModalInstance = null;
    let deleteRaciConfirmModalInstance = null;
    let mainRaciMatrixModalInstance = null;
    let raciIdToDelete = null;
    let raciHrIdToDelete = null;

    document.addEventListener('DOMContentLoaded', function () {
        const mainRaciEl = document.getElementById('raciMatrixModal');
        if (mainRaciEl) {
            mainRaciMatrixModalInstance = bootstrap.Modal.getOrCreateInstance(mainRaciEl);
        }

        if (document.getElementById('addRaciModal')) {
            raciModalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('addRaciModal'));
        }
        if (document.getElementById('deleteRaciConfirmModal')) {
            deleteRaciConfirmModalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteRaciConfirmModal'));
        }
    });

    function openNewRaciModal() {
        document.getElementById('formAddRaci').reset();
        raciModalInstance.show();
    }

    function openInlineRaciModal(projectId, activityName, hrId) {
        document.getElementById('formAddRaci').reset();
        document.getElementById('new_raci_project_id').value = projectId;
        document.getElementById('new_raci_activity').value = activityName;
        document.getElementById('new_raci_hr_id').value = hrId;
        raciModalInstance.show();
    }

    function refreshMatrixContent() {
        fetch(window.location.href)
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                document.getElementById('raci-matrix-container').innerHTML = doc.getElementById('raci-matrix-container').innerHTML;

                document.body.classList.add('modal-open');
                document.body.style.overflow = 'hidden';
            })
            .catch(err => console.error("Erro ao atualizar matriz:", err));
    }

    function saveRaci(event) {
        event.preventDefault();
        if (document.activeElement) document.activeElement.blur();

        const btn = document.getElementById('btnSaveRaci');
        const originalText = btn.innerHTML;
        btn.disabled = true;

        const formData = new FormData(document.getElementById('formAddRaci'));
        const hrId = document.getElementById('new_raci_hr_id').value;
        let postUrl = "{{ route('companies.hr.raci.store', ['company' => $company->company_id, 'hr_id' => ':hrId']) }}".replace(':hrId', hrId);

        fetch(postUrl, {
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
                    raciModalInstance.hide();
                    refreshMatrixContent();
                } else {
                    alert(data.message || "Erro ao salvar");
                }
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
    }

    function deleteRaci(hrId, raciId) {
        raciIdToDelete = raciId;
        raciHrIdToDelete = hrId;
        deleteRaciConfirmModalInstance.show();
    }

    function executeDeleteRaci() {
        if (document.activeElement) document.activeElement.blur();
        if (!raciIdToDelete || !raciHrIdToDelete) return;

        let url = "{{ route('companies.hr.raci.destroy', ['company' => $company->company_id, 'hr_id' => ':hrId', 'raci_id' => ':id']) }}"
            .replace(':hrId', raciHrIdToDelete)
            .replace(':id', raciIdToDelete);

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
                    deleteRaciConfirmModalInstance.hide();
                    refreshMatrixContent();
                }
            })
            .finally(() => {
                raciIdToDelete = null;
                raciHrIdToDelete = null;
            });
    }
</script>