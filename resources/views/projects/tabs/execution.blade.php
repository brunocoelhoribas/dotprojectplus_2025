<div class="card border-0 shadow-none">
    <div class="card-body p-0">

        <div class="d-flex justify-content-between align-items-center mb-4 bg-light p-3 rounded border">

            <div class="d-flex align-items-center gap-3">
                <div>
                    <label for="execution_user_filter" class="form-label small fw-bold text-muted mb-1">
                        {{ __('execution/view.filter.user') }}
                    </label>
                    <select class="form-select form-select-sm" id="execution_user_filter" onchange="filterExecution()">
                        <option value="">{{ __('execution/view.filter.all_users') }}</option>
                        @if(isset($projectUsers))
                            @foreach($projectUsers as $user)
                                <option value="{{ $user->user_id }}">{{ $user->user_username ?? $user->contact->first_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="vr h-100 mx-2 text-muted"></div>

                <div class="form-check form-switch pt-4">
                    <input class="form-check-input" type="checkbox" id="show_completed" onchange="filterExecution()">
                    <label class="form-check-label small" for="show_completed">{{ __('execution/view.filter.show_completed') }}</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#newLogModal">
                    <i class="bi bi-clock-history me-1"></i> {{ __('execution/view.actions.new_log') }}
                </button>
            </div>
        </div>

        <div id="execution-table-container" style="min-height: 200px;">
            @include('projects.partials.execution_table')
        </div>

    </div>
</div>

<div class="modal fade" id="newLogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formNewLog" onsubmit="saveLog(event)">

            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title h6 fw-bold text-dark">
                        <i class="bi bi-stopwatch me-2"></i>{{ __('execution/view.log.title') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">{{ __('execution/view.log.task') }} <span class="text-danger">*</span></label>
                        <select name="task_id" id="modal_task_select" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach($wbsItems as $item)
                                @foreach($item->tasks as $task)
                                    <option value="{{ $task->task_id }}">
                                        {{ $item->number }} - {{ $task->task_name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">{{ __('execution/view.log.date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="log_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">{{ __('execution/view.log.hours') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="0.1" name="hours" class="form-control" required placeholder="0.0">
                                <span class="input-group-text small">h</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">{{ __('execution/view.log.description') }}</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-check bg-light p-2 rounded border">
                        <input class="form-check-input ms-1" type="checkbox" name="concluded" value="1" id="checkConcluded">
                        <label class="form-check-label ms-2 fw-bold small text-dark" for="checkConcluded">
                            {{ __('execution/view.log.mark_concluded') }} (100%)
                        </label>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" id="btnSaveLog" class="btn btn-primary btn-sm px-4">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title h6 fw-bold">
                    <i class="bi bi-list-columns-reverse me-2"></i> {{ __('execution/view.actions.view_logs') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="historyModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterExecution() {
        const userId = document.getElementById('execution_user_filter').value;
        const showCompleted = document.getElementById('show_completed').checked ? 1 : 0;
        const container = document.getElementById('execution-table-container');

        container.style.opacity = '0.5';

        const url = `{{ route('projects.execution.index', $project->project_id) }}?user_id=${userId}&show_completed=${showCompleted}`;

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                container.style.opacity = '1';
            })
            .catch(err => {
                console.error('Erro ao atualizar tabela:', err);
                container.style.opacity = '1';
            });
    }

    function saveLog(event) {
        event.preventDefault();

        const form = document.getElementById('formNewLog');
        const formData = new FormData(form);
        const btnSave = document.getElementById('btnSaveLog');
        const originalBtnText = btnSave.innerHTML;

        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Salvando...';

        const url = `{{ route('projects.execution.log.store', $project->project_id) }}`;

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modalEl = document.getElementById('newLogModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                    form.reset();

                    filterExecution();

                } else {
                    console.log('Erro ao salvar: ' + JSON.stringify(data.errors || 'Erro desconhecido'));
                }
            })
            .catch(err => {
                console.error(err);
            })
            .finally(() => {
                btnSave.disabled = false;
                btnSave.innerHTML = originalBtnText;
            });
    }

    function openLogModal(taskId) {
        const select = document.getElementById('modal_task_select');
        const modalEl = document.getElementById('newLogModal');

        if(select) {
            select.value = taskId;
        }

        var bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
    }

    let currentHistoryTaskId = null;

    function openHistoryModal(taskId) {
        currentHistoryTaskId = taskId;
        var myModal = new bootstrap.Modal(document.getElementById('historyModal'));
        myModal.show();
        loadHistoryContent(taskId);
    }

    function loadHistoryContent(taskId) {
        const content = document.getElementById('historyModalBody');
        content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';

        let url = "{{ route('projects.tasks.logs.list', ['project' => $project->project_id, 'task' => ':id']) }}";
        url = url.replace(':id', taskId);

        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error(res.statusText);
                return res.text();
            })
            .then(html => {
                content.innerHTML = html;
            })
            .catch(err => {
                console.error(err);
                content.innerHTML = '<div class="text-danger text-center p-3">Erro ao carregar histórico (' + err.message + ').</div>';
            });
    }

    function showDeleteConfirm(logId) {
        document.getElementById(`btn-delete-action-${logId}`).classList.add('d-none');
        document.getElementById(`confirm-card-${logId}`).classList.remove('d-none');
    }

    function cancelDelete(logId) {
        document.getElementById(`confirm-card-${logId}`).classList.add('d-none');
        document.getElementById(`btn-delete-action-${logId}`).classList.remove('d-none');
    }

    function deleteLog(logId) {
        const confirmBtn = document.getElementById(`btn-confirm-delete-${logId}`);
        if(confirmBtn) confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        let url = "{{ route('projects.tasks.logs.destroy', ['project' => $project->project_id, 'log' => ':id']) }}";

        url = url.replace(':id', logId);

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if(currentHistoryTaskId) loadHistoryContent(currentHistoryTaskId);

                    filterExecution();
                } else {
                    alert('Erro ao excluir: ' + (data.message || 'Erro desconhecido'));
                    cancelDelete(logId);
                }
            })
            .catch(err => {
                console.error('Erro na exclusão:', err);
                alert('Não foi possível excluir o registro.');
                cancelDelete(logId);
            });
    }
</script>
