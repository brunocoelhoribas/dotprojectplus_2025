@php
    use App\Models\Company\CompanyOrganogramRole;

    $organogramItems = CompanyOrganogramRole::where('company_id', $company->company_id)
                        ->orderBy('sort_order', 'asc')
                        ->get();

    $availableRoles = $company->roles()->orderBy('human_resources_role_name')->get();

    $permissionsMap = [];
    foreach($availableRoles as $r) {
        $permissionsMap[$r->human_resources_role_name] = $r->can_delete;
    }
@endphp

<form id="formOrganogram" action="{{ route('companies.organogram.update', $company->company_id) }}" method="POST">
    @csrf

    <input type="hidden" name="organogram_data" id="organogram_data">

    <div class="table-responsive mb-3">
        <table class="table table-bordered table-sm align-middle" id="tb_organogram">
            <thead style="background-color: #e0e0e0;">
            <tr class="text-center small fw-bold">
                <th style="width: 80px; background-color: #FFC107;">{{ __('companies/view.organogram.order') }}</th>
                <th style="width: 100px; background-color: #FFC107;">{{ __('companies/view.organogram.indentation') }}</th>
                <th style="background-color: #FFC107;">{{ __('companies/view.organogram.role') }}</th>
                <th style="width: 100px; background-color: #FFC107;">{{ __('companies/view.organogram.actions') }}</th>
            </tr>
            </thead>
            <tbody id="organogram_body">
            @foreach($organogramItems as $item)
                @php
                    $canDelete = $permissionsMap[$item->role_name] ?? true;
                @endphp

                <tr data-level="{{ $item->identation ?? 0 }}" data-can-delete="{{ $canDelete ? 'true' : 'false' }}">
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-light border" onclick="moveRow(this, -1)">↑</button>
                            <button type="button" class="btn btn-light border" onclick="moveRow(this, 1)">↓</button>
                        </div>
                    </td>

                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-light border" onclick="changeIndent(this, -1)">←</button>
                            <button type="button" class="btn btn-light border" onclick="changeIndent(this, 1)">→</button>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex align-items-center role-container" style="padding-left: {{ ($item->identation ?? 0) * 30 }}px;">
                            <span class="indent-indicator text-muted me-2">
                                @if(($item->identation ?? 0) > 0) └ @endif
                            </span>

                            <select class="form-select form-select-sm role-select" onchange="updateRowPermission(this)">
                                @foreach($availableRoles as $roleDef)
                                    <option value="{{ $roleDef->human_resources_role_name }}"
                                            data-can-delete="{{ $roleDef->can_delete ? 'true' : 'false' }}"
                                        {{ $roleDef->human_resources_role_name === $item->role_name ? 'selected' : '' }}>
                                        {{ $roleDef->human_resources_role_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </td>

                    <td class="text-center action-cell">
                        @if($canDelete)
                            <button type="button" class="btn btn-xs btn-outline-danger btn-delete" onclick="prepareRemoveRow(this)">
                                <i class="bi bi-trash"></i>
                            </button>
                        @else
                            <span class="badge bg-warning text-dark cursor-help" title="Este papel não pode ser deletado pois está em uso.">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end gap-2 bg-light p-3 rounded border">
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addNewRow()">
            {{ __('companies/view.organogram.add') }}
        </button>

        <button type="button" class="btn btn-primary btn-sm" onclick="saveOrganogram()">
            {{ __('companies/view.organogram.save') }}
        </button>

        <a href="{{ route('companies.show', $company->company_id) }}" class="btn btn-outline-dark btn-sm">
            {{ __('companies/view.organogram.cancel') }}
        </a>
    </div>
</form>

<template id="row_template">
    <tr data-level="0" data-can-delete="true">
        <td class="text-center">
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-light border" onclick="moveRow(this, -1)">↑</button>
                <button type="button" class="btn btn-light border" onclick="moveRow(this, 1)">↓</button>
            </div>
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-light border" onclick="changeIndent(this, -1)">←</button>
                <button type="button" class="btn btn-light border" onclick="changeIndent(this, 1)">→</button>
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center role-container" style="padding-left: 0px;">
                <span class="indent-indicator text-muted me-2"></span>
                <select class="form-select form-select-sm role-select" onchange="updateRowPermission(this)">
                    <option value="">{{ __('companies/view.organogram.select_placeholder') }}</option>
                    @foreach($availableRoles as $roleDef)
                        <option value="{{ $roleDef->human_resources_role_name }}"
                                data-can-delete="{{ $roleDef->can_delete ? 'true' : 'false' }}">
                            {{ $roleDef->human_resources_role_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </td>
        <td class="text-center action-cell">
            <button type="button" class="btn btn-xs btn-outline-danger btn-delete" onclick="prepareRemoveRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
</template>

<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title h6 fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Atenção
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ __('companies/view.organogram.js.confirm_remove') }}
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    {{ __('companies/view.organogram.cancel') }}
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">
                    Excluir
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="statusMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" id="statusModalHeader">
                <h5 class="modal-title h6 fw-bold" id="statusModalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
    let rowToDelete = null;
    let deleteModal = null;
    let statusModal = null;

    document.addEventListener('DOMContentLoaded', function() {
        deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        statusModal = new bootstrap.Modal(document.getElementById('statusMessageModal'));

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if(rowToDelete) {
                rowToDelete.remove();
                rowToDelete = null;
            }
            deleteModal.hide();
        });
    });

    function showMessage(title, message, type) {
        const header = document.getElementById('statusModalHeader');
        const titleEl = document.getElementById('statusModalTitle');
        const bodyEl = document.getElementById('statusModalBody');

        titleEl.innerText = title;

        if (type === 'success') {
            header.className = 'modal-header bg-success text-white';
            bodyEl.innerHTML = `<i class="bi bi-check-circle-fill text-success display-4 mb-3 d-block"></i><span class="fw-bold">${message}</span>`;
        } else {
            header.className = 'modal-header bg-danger text-white';
            bodyEl.innerHTML = `<i class="bi bi-x-circle-fill text-danger display-4 mb-3 d-block"></i><span class="fw-bold">${message}</span>`;
        }

        statusModal.show();
    }

    function moveRow(btn, direction) {
        const row = btn.closest('tr');
        const tbody = row.parentNode;
        const siblings = Array.from(tbody.children);
        const index = siblings.indexOf(row);
        if (direction === -1 && index > 0) {
            tbody.insertBefore(row, siblings[index - 1]);
        } else if (direction === 1 && index < siblings.length - 1) {
            tbody.insertBefore(row, siblings[index + 1].nextSibling);
        }
    }

    function changeIndent(btn, change) {
        const row = btn.closest('tr');
        let level = parseInt(row.getAttribute('data-level')) || 0;
        level += change;
        if (level < 0) level = 0;
        if (level > 10) level = 10;
        row.setAttribute('data-level', level);
        const container = row.querySelector('.role-container');
        const indicator = row.querySelector('.indent-indicator');
        container.style.paddingLeft = (level * 30) + 'px';
        indicator.innerHTML = level > 0 ? '└' : '';
    }

    function prepareRemoveRow(btn) {
        rowToDelete = btn.closest('tr');
        deleteModal.show();
    }

    function addNewRow() {
        const template = document.getElementById('row_template');
        const clone = template.content.cloneNode(true);
        document.getElementById('organogram_body').appendChild(clone);
    }

    function updateRowPermission(select) {
        const option = select.options[select.selectedIndex];
        const canDelete = option.getAttribute('data-can-delete') === 'true';
        const row = select.closest('tr');
        const actionCell = row.querySelector('.action-cell');

        row.setAttribute('data-can-delete', canDelete);

        if (canDelete) {
            actionCell.innerHTML = `
                <button type="button" class="btn btn-xs btn-outline-danger btn-delete" onclick="prepareRemoveRow(this)">
                    <i class="bi bi-trash"></i>
                </button>`;
        } else {
            actionCell.innerHTML = `
                <span class="badge bg-warning text-dark" title="Este papel está em uso">
                    <i class="bi bi-lock-fill"></i>
                </span>`;
        }
    }

    function saveOrganogram() {
        const rows = document.querySelectorAll('#organogram_body tr');
        let data = [];

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const select = row.querySelector('.role-select');
            const roleName = select.value;
            const level = row.getAttribute('data-level');

            if (roleName) {
                data.push({
                    role_name: roleName,
                    level: parseInt(level)
                });
            }
        }

        if (data.length === 0) {
            showMessage('Atenção', '{{ __('companies/view.organogram.js.alert_empty') }}', 'error');
            return;
        }

        const btnSave = document.querySelector('button[onclick="saveOrganogram()"]');
        const originalText = btnSave.innerHTML;
        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        const formData = new FormData();
        formData.append('organogram_data', JSON.stringify(data));

        fetch("{{ route('companies.organogram.update', $company->company_id) }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showMessage('Sucesso', data.message, 'success');
                } else {
                    showMessage('Erro', data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showMessage('Erro', 'Erro de conexão ao salvar.', 'error');
            })
            .finally(() => {
                btnSave.disabled = false;
                btnSave.innerHTML = originalText;
            });
    }
</script>
