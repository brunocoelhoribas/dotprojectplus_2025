@php
    use App\Models\User\User;
    use Illuminate\Support\Facades\DB;

    $users = User::whereHas('contact', static function ($query) use ($company) {
        $query->where('contact_company', $company->company_id);
    })->with(['contact', 'humanResource.roles'])->get();

    $existingHrUserIds = \App\Models\HumanResource\HumanResource::pluck('human_resource_user_id')->toArray();
    $availableUsers = $users->whereNotIn('user_id', $existingHrUserIds);
    $availableRoles = DB::table('dotp_human_resources_role')->orderBy('human_resources_role_name')->get();
@endphp
<div id="hr-content">
    <div class="d-flex justify-content-end mb-3 mt-3">
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

                <tr class="{{ $rowClass }}" id="hr-row-{{ $hrId ?? 'u'.$user->user_id }}">
                    <td class="text-center">
                        @if($hasHr)
                            <a href="{{ route('companies.hr.show', ['company' => $company->company_id, 'hr_id' => $hrId]) }}"
                               class="text-dark" title="Ver detalhes/Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        @else
                            <span class="text-muted opacity-25" title="Recurso não configurado">
                                <i class="bi bi-pencil-square"></i>
                            </span>
                        @endif
                    </td>
                    <td class="fw-bold text-dark">
                        {{ $user->contact->full_name ?? $user->user_username }}
                    </td>
                    <td class="small text-muted">
                        {{ $rolesText ?: '--' }}
                    </td>
                    <td class="text-center">
                        @if($hasHr)
                            @if($canDelete)
                                <button type="button" class="btn btn-xs btn-outline-danger"
                                        onclick="deleteHumanResource({{ $hrId }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @else
                                <span class="small text-success d-inline-block text-start lh-sm"
                                      style="font-size: 0.75rem;">
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

    <div class="d-flex align-items-center gap-4 small mt-3 px-2">
        <span class="fw-bold text-muted">{{ __('companies/view.hr.key') }}:</span>
        <div class="d-flex align-items-center gap-2">
            <div class="border border-secondary" style="width: 20px; height: 12px; background-color: #FFFFFF;"></div>
            <span>{{ __('companies/view.hr.configured') }}</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="border border-secondary" style="width: 20px; height: 12px; background-color: #f8d7da;"></div>
            <span>{{ __('companies/view.hr.not_configured') }}</span>
        </div>
    </div>
</div>

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
                        <label class="form-label fw-bold small mb-2 d-block">Tipo de Cadastro</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="creation_type" id="type_existing"
                                       value="existing" checked onchange="toggleHrCreationType()">
                                <label class="form-check-label small fw-bold" for="type_existing">
                                    Selecionar Usuário Existente
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="creation_type" id="type_new"
                                       value="new" onchange="toggleHrCreationType()">
                                <label class="form-check-label small fw-bold" for="type_new">
                                    Cadastrar Novo Usuário
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="div_existing_user" class="mb-3">
                        <label for="user_id" class="form-label fw-bold small">
                            Usuário Cadastrado <span class="text-danger">*</span>
                        </label>
                        <select name="user_id" id="user_id" class="form-select form-select-sm" required>
                            <option value="">Selecione um usuário disponível...</option>
                            @forelse($availableUsers as $u)
                                <option value="{{ $u->user_id }}">
                                    {{ $u->contact ? $u->contact->full_name : $u->user_username }}
                                </option>
                            @empty
                                <option value="" disabled>Todos os usuários da empresa já estão configurados.</option>
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
                            {{ __('companies/view.hr.details.roles') ?? 'Papéis' }}
                        </label>
                        <select name="roles[]" id="roles" class="form-select form-select-sm" multiple
                                style="height: 90px;">
                            @foreach($availableRoles as $role)
                                <option
                                    value="{{ $role->human_resources_role_id }}">{{ $role->human_resources_role_name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text small text-muted">Segure Ctrl (ou Cmd) para selecionar múltiplos.</div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label
                            class="form-label fw-bold small">{{ __('companies/view.hr.details.curriculum') ?? 'Currículo (Lattes)' }}</label>
                        <input type="url" name="human_resource_lattes_url" class="form-control form-control-sm"
                               placeholder="http://lattes.cnpq.br/...">
                    </div>

                    <h6 class="fw-bold small border-bottom pb-1 mt-4">{{ __('companies/view.hr.details.section_hours') ?? 'Horas de trabalho semanais' }}</h6>
                    <div class="row g-2">
                        @php
                            $days = [
                                'sun' => __('companies/view.hr.days.sun') ?? 'Dom',
                                'mon' => __('companies/view.hr.days.mon') ?? 'Seg',
                                'tue' => __('companies/view.hr.days.tue') ?? 'Ter',
                                'wed' => __('companies/view.hr.days.wed') ?? 'Qua',
                                'thu' => __('companies/view.hr.days.thu') ?? 'Qui',
                                'fri' => __('companies/view.hr.days.fri') ?? 'Sex',
                                'sat' => __('companies/view.hr.days.sat') ?? 'Sáb'
                            ];
                        @endphp

                        @foreach($days as $key => $label)
                            <div class="col-md-6 d-flex align-items-center">
                                <label class="small text-muted me-2 text-end" style="width: 80px;">{{ $label }}:</label>
                                <input type="number" name="human_resource_{{ $key }}"
                                       class="form-control form-control-sm" min="0" max="24" value="0">
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
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('companies/view.hr.messages.error_title') ?? 'Atenção' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ __('companies/view.hr.messages.confirm_delete_hr') ?? 'Tem certeza que deseja excluir o Recurso Humano selecionado?' }}
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    {{ __('companies/view.hr.actions.cancel') ?? 'Cancelar' }}
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="executeDeleteHumanResource()">
                    {{ __('companies/view.hr.table.actions') ?? 'Excluir' }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let hrModalInstance = null;
    let deleteHrConfirmModalInstance = null;
    let hrIdToDelete = null;

    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('newHrModal');
        if (modalEl) hrModalInstance = new bootstrap.Modal(modalEl);

        const deleteModalEl = document.getElementById('deleteHrConfirmModal');
        if (deleteModalEl) deleteHrConfirmModalInstance = new bootstrap.Modal(deleteModalEl);
    });

    function toggleHrCreationType() {
        const isNew = document.getElementById('type_new').checked;
        const divExisting = document.getElementById('div_existing_user');
        const divNew = document.getElementById('div_new_user');

        if (isNew) {
            divExisting.classList.add('d-none');
            divNew.classList.remove('d-none');
            document.getElementById('user_id').required = false;
            document.getElementById('first_name').required = true;
            document.getElementById('last_name').required = true;
        } else {
            divNew.classList.add('d-none');
            divExisting.classList.remove('d-none');
            document.getElementById('user_id').required = true;
            document.getElementById('first_name').required = false;
            document.getElementById('last_name').required = false;
        }
    }

    function saveHumanResource(event) {
        event.preventDefault();

        const form = document.getElementById('formNewHr');
        const btn = document.getElementById('btnSubmitHr');
        const originalText = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        const formData = new FormData(form);

        fetch("{{ route('companies.hr.store', $company->company_id) }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                    hrModalInstance.hide();
                    form.reset();
                    if (typeof showMessage === 'function') {
                        showMessage("{{ __('companies/view.hr.messages.success_title') ?? 'Sucesso' }}", data.message, 'success');
                    }
                    setTimeout(() => window.location.reload(), 1500);
                }
            })
            .catch(err => {
                console.error(err);
                let msg = err.message || 'Erro ao processar requisição.';
                if (typeof showMessage === 'function') {
                    showMessage("{{ __('companies/view.hr.messages.error_title') ?? 'Erro' }}", msg, 'error');
                }
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
    }

    function deleteHumanResource(hrId) {
        hrIdToDelete = hrId;
        if (deleteHrConfirmModalInstance) {
            deleteHrConfirmModalInstance.show();
        }
    }

    function executeDeleteHumanResource() {
        if (!hrIdToDelete) return;

        let url = "{{ route('companies.hr.destroy', ['company' => $company->company_id, 'hr_id' => ':id']) }}".replace(':id', hrIdToDelete);

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
                    deleteHrConfirmModalInstance.hide();
                    const row = document.getElementById('hr-row-' + hrIdToDelete);
                    if (row) row.remove();

                    if (typeof showMessage === 'function') {
                        showMessage("{{ __('companies/view.hr.messages.success_title') ?? 'Excluído' }}", data.message, 'success');
                    }
                    setTimeout(() => window.location.reload(), 1000);

                } else {
                    if (typeof showMessage === 'function') {
                        showMessage("{{ __('companies/view.hr.messages.error_title') ?? 'Atenção' }}", data.message, 'error');
                    }
                }
            })
            .catch(err => {
                console.error('Erro na exclusão:', err);
                if (typeof showMessage === 'function') {
                    showMessage("{{ __('companies/view.hr.messages.error_title') ?? 'Erro' }}", 'Erro ao excluir o recurso.', 'error');
                }
            })
            .finally(() => {
                hrIdToDelete = null;
            });
    }
</script>
