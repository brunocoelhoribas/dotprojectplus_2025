<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark m-0">{{ __('planning/view.acquisition.title') }}</h6>
        <button class="btn btn-sm btn-light border shadow-sm" onclick="openAcquisitionModal()">
            <i class="bi bi-plus-lg me-1"></i> {{ __('planning/view.acquisition.btn_new') }}
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0">
            <thead class="table-light">
            <tr class="text-center align-middle">
                <th width="12%">{{ __('planning/view.acquisition.table.item') }}</th>
                <th width="10%">{{ __('planning/view.acquisition.table.contract') }}</th>
                <th width="15%">{{ __('planning/view.acquisition.table.docs') }}</th>
                <th width="18%">{{ __('planning/view.acquisition.table.selection') }}</th>
                <th width="15%">{{ __('planning/view.acquisition.table.requirements') }}</th>
                <th width="15%">{{ __('planning/view.acquisition.table.management') }}</th>
                <th width="15%">{{ __('planning/view.acquisition.table.roles') }}</th>
                <th width="40px"></th>
            </tr>
            </thead>
            <tbody class="bg-white">
            @forelse($acquisitions as $acq)
                <tr>
                    <td class="fw-bold">{{ $acq->items_to_be_acquired }}</td>
                    <td class="text-center">
                        @php
                            $key = 'planning/view.acquisition.modal.options.' . $acq->contract_type;
                            $label = \Illuminate\Support\Facades\Lang::has($key) ? __($key) : $acq->contract_type;
                        @endphp
                        <span class="badge bg-light text-dark border">{{ $label }}</span>
                    </td>
                    <td>{{ $acq->documents_to_acquisition }}</td>
                    <td>
                        @if($acq->criteria->count() > 0)
                            <ul class="list-unstyled mb-0">
                                @foreach($acq->criteria as $c)
                                    <li class="mb-1 border-bottom pb-1 border-light">
                                        <i class="bi bi-check2-circle text-warning small me-1"></i>
                                        {{ $c->criteria }}
                                        <span class="text-muted ms-1">({{ $c->weight }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($acq->requirements->count() > 0)
                            <ul class="list-unstyled mb-0">
                                @foreach($acq->requirements as $r)
                                    <li class="mb-1"><i class="bi bi-dot"></i> {{ $r->requirement }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $acq->supplier_management_process }}</td>
                    <td>
                        @if($acq->roles->count() > 0)
                            <ul class="list-unstyled mb-0">
                                @foreach($acq->roles as $role)
                                    <li class="mb-1 border-bottom pb-1 border-light">
                                        <strong>{{ $role->role }}:</strong> {{ $role->responsability }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-xs btn-link text-dark p-0 me-1" onclick="editAcquisition({{ $acq->id }})">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <form action="{{ route('projects.acquisition.destroy', ['project' => $project->project_id, 'acquisition' => $acq->id]) }}"
                              method="POST"
                              onsubmit="askConfirmation(event, '{{ __('planning/view.quality.confirm.delete') }}')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs text-danger border-0 bg-transparent p-0"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Nenhum planejamento registrado.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="acquisitionModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form id="acquisitionForm" action="{{ route('projects.acquisition.store', $project->project_id) }}" method="POST" onsubmit="submitAjaxForm(event)">
                @csrf
                <input type="hidden" name="_method" id="acqFormMethod" value="POST">

                <div class="modal-header bg-warning py-2">
                    <h5 class="modal-title text-dark fw-bold fs-6">{{ __('planning/view.acquisition.modal.title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-white p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">{{ __('planning/view.acquisition.modal.label_item') }}</label>
                            <input type="text" name="items_to_be_acquired" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">{{ __('planning/view.acquisition.modal.label_contract') }}</label>
                            <select name="contract_type" class="form-select">
                                <option value="fixed_price">{{ __('planning/view.acquisition.modal.options.fixed_price') }}</option>
                                <option value="time_material">{{ __('planning/view.acquisition.modal.options.time_material') }}</option>
                                <option value="cost_reimbursable">{{ __('planning/view.acquisition.modal.options.cost_reimbursable') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">{{ __('planning/view.acquisition.modal.label_docs') }}</label>
                        <textarea name="documents_to_acquisition" class="form-control" rows="2"></textarea>
                    </div>

                    <hr class="text-muted opacity-25">

                    <div class="row mb-4">
                        <div class="col-md-6 border-end">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold small text-dark mb-0">
                                    {{ __('planning/view.acquisition.modal.label_selection') }}
                                </label>
                                <button type="button" class="btn btn-xs btn-outline-warning text-dark fw-bold" onclick="addCriteriaRow()">
                                    {{ __('planning/view.acquisition.modal.btn_add_criteria') }}
                                </button>
                            </div>
                            <div id="criteriaContainer" class="bg-light p-2 rounded"></div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold small text-dark mb-0">
                                    {{ __('planning/view.acquisition.modal.label_requirements') }}
                                </label>
                                <button type="button" class="btn btn-xs btn-outline-warning text-dark fw-bold" onclick="addReqRow()">
                                    {{ __('planning/view.acquisition.modal.btn_add_req') }}
                                </button>
                            </div>
                            <div id="reqContainer" class="bg-light p-2 rounded"></div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25">

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">{{ __('planning/view.acquisition.modal.label_management') }}</label>
                        <textarea name="supplier_management_process" class="form-control" rows="2"></textarea>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold small text-dark mb-0">
                                {{ __('planning/view.acquisition.modal.label_roles') }}
                            </label>
                            <button type="button" class="btn btn-xs btn-outline-warning text-dark fw-bold" onclick="addRoleRow()">
                                {{ __('planning/view.acquisition.modal.btn_add_role') }}
                            </button>
                        </div>
                        <div id="roleContainer" class="bg-light p-2 rounded"></div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-light border shadow-sm" data-bs-dismiss="modal">
                        {{ __('planning/view.acquisition.modal.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary px-4">
                        {{ __('planning/view.acquisition.modal.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    {{ __('planning/view.quality.modals.confirmation_title') }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p id="confirmationMessage" class="mb-0 fw-bold text-dark"></p>
            </div>
            <div class="modal-footer bg-light justify-content-center p-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    {{ __('planning/view.quality.modals.confirm_no') }}
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="executePendingAction()">
                    {{ __('planning/view.quality.modals.confirm_yes') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let storeAcqRoute = "{{ route('projects.acquisition.store', $project->project_id) }}";
    let updateAcqBase = "{{ url('projects/' . $project->project_id . '/acquisition') }}";
    let pendingForm = null;

    function openAcquisitionModal() {
        let form = document.getElementById('acquisitionForm');
        form.reset();
        form.action = storeAcqRoute;
        document.getElementById('acqFormMethod').value = 'POST';

        document.getElementById('criteriaContainer').innerHTML = '';
        document.getElementById('reqContainer').innerHTML = '';
        document.getElementById('roleContainer').innerHTML = '';

        addCriteriaRow();
        addReqRow();
        addRoleRow();

        new bootstrap.Modal(document.getElementById('acquisitionModal')).show();
    }

    function editAcquisition(id) {
        let url = `${updateAcqBase}/${id}`;
        fetch(url)
            .then(res => res.json())
            .then(data => {
                let form = document.getElementById('acquisitionForm');
                form.action = url;
                document.getElementById('acqFormMethod').value = 'PUT';

                form.querySelector('[name="items_to_be_acquired"]').value = data.items_to_be_acquired;
                form.querySelector('[name="contract_type"]').value = data.contract_type;
                form.querySelector('[name="documents_to_acquisition"]').value = data.documents_to_acquisition || '';
                form.querySelector('[name="supplier_management_process"]').value = data.supplier_management_process || '';

                let cContainer = document.getElementById('criteriaContainer');
                cContainer.innerHTML = '';
                if(data.criteria && data.criteria.length > 0) {
                    data.criteria.forEach(c => addCriteriaRow(c.criteria, c.weight));
                } else { addCriteriaRow(); }

                let rContainer = document.getElementById('reqContainer');
                rContainer.innerHTML = '';
                if(data.requirements && data.requirements.length > 0) {
                    data.requirements.forEach(r => addReqRow(r.requirement));
                } else { addReqRow(); }

                let roleContainer = document.getElementById('roleContainer');
                roleContainer.innerHTML = '';
                if(data.roles && data.roles.length > 0) {
                    data.roles.forEach(r => addRoleRow(r.role, r.responsability));
                } else { addRoleRow(); }

                new bootstrap.Modal(document.getElementById('acquisitionModal')).show();
            });
    }

    function addCriteriaRow(val = '', weight = '') {
        let html = `
            <div class="input-group input-group-sm mb-2">
                <span class="input-group-text bg-white"><i class="bi bi-list-check"></i></span>
                <input type="text" name="criteria_name[]" class="form-control" placeholder="{{ __('planning/view.acquisition.modal.placeholders.criteria') }}" value="${val}">
                <input type="number" name="criteria_weight[]" class="form-control" placeholder="{{ __('planning/view.acquisition.modal.placeholders.weight') }}" value="${weight}" style="max-width: 100px;">
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-trash"></i></button>
            </div>`;
        document.getElementById('criteriaContainer').insertAdjacentHTML('beforeend', html);
    }

    function addReqRow(val = '') {
        let html = `
            <div class="input-group input-group-sm mb-2">
                <span class="input-group-text bg-white"><i class="bi bi-exclamation-circle"></i></span>
                <input type="text" name="req_name[]" class="form-control" placeholder="{{ __('planning/view.acquisition.modal.placeholders.req') }}" value="${val}">
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-trash"></i></button>
            </div>`;
        document.getElementById('reqContainer').insertAdjacentHTML('beforeend', html);
    }

    function addRoleRow(role = '', resp = '') {
        let html = `
            <div class="input-group input-group-sm mb-2">
                <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                <input type="text" name="role_name[]" class="form-control" placeholder="{{ __('planning/view.acquisition.modal.placeholders.role') }}" value="${role}">
                <input type="text" name="role_resp[]" class="form-control" placeholder="{{ __('planning/view.acquisition.modal.placeholders.resp') }}" value="${resp}">
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-trash"></i></button>
            </div>`;
        document.getElementById('roleContainer').insertAdjacentHTML('beforeend', html);
    }

    function askConfirmation(event, message) {
        event.preventDefault();
        pendingForm = event.target;
        document.getElementById('confirmationMessage').innerText = message;
        new bootstrap.Modal(document.getElementById('confirmationModal')).show();
    }

    function executePendingAction() {
        if (pendingForm) {
            let modalEl = document.getElementById('confirmationModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            processAjaxForm(pendingForm);
        }
    }

    function submitAjaxForm(event) {
        event.preventDefault();
        processAjaxForm(event.target);
    }

    function processAjaxForm(form) {
        let url = form.action;
        let formData = new FormData(form);
        let submitBtn = form.querySelector('button[type="submit"]');

        let originalText = '';
        if(submitBtn) {
            originalText = submitBtn.innerText;
            submitBtn.innerText = 'Salvando...';
            submitBtn.disabled = true;
        }

        fetch(url, {
            method: form.method || 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(async response => {
                let data = await response.json();

                if (!response.ok) {
                    let errorMsg = data.message || 'Erro desconhecido.';
                    if (data.errors) {
                        errorMsg += '\n';
                        for (const [key, value] of Object.entries(data.errors)) {
                            errorMsg += `\n- ${value}`;
                        }
                    }
                    throw new Error(errorMsg);
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    closeAllModais();
                    reloadTab();
                } else {
                    console.warn('Sucesso falso:', data.message);
                }
            })
            .catch(error => {
                console.error('Erro na requisição AJAX:', error.message);
                alert('Erro: ' + error.message);
            })
            .finally(() => {
                if(submitBtn) {
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                }
                pendingForm = null;
            });
    }

    function closeAllModais() {
        document.querySelectorAll('.modal.show').forEach(el => {
            let instance = bootstrap.Modal.getInstance(el);
            if (instance) instance.hide();
        });
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
    }

    function reloadTab() {
        let tabBtn = document.querySelector('a[onclick*="acquisition"], button[onclick*="acquisition"]');
        if (tabBtn) {
            tabBtn.click();
            return;
        }
        if (typeof loadTab === 'function') {
            let fakeEvent = {
                preventDefault: function () {},
                target: document.body
            };
            loadTab(fakeEvent, 'acquisition');
        }
    }
</script>
