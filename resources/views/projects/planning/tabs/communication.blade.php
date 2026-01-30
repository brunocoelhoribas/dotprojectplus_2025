<div class="container-fluid p-0">
    <div class="mb-3">
        <button class="btn btn-sm btn-light border shadow-sm me-1" onclick="openCommunicationModal()">
            {{ __('planning/view.communication.buttons.new_event') }}
        </button>
        <button class="btn btn-sm btn-light border shadow-sm me-1" onclick="openChannelModal()">
            {{ __('planning/view.communication.buttons.new_channel') }}
        </button>
        <button class="btn btn-sm btn-light border shadow-sm" onclick="openFrequencyModal()">
            {{ __('planning/view.communication.buttons.new_frequency') }}
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0">
            <thead class="bg-warning text-dark">
            <tr>
                <th style="width: 25%;">{{ __('planning/view.communication.table.title') }}</th>
                <th style="width: 45%;">{{ __('planning/view.communication.table.communication') }}</th>
                <th style="width: 12%;">{{ __('planning/view.communication.table.channel') }}</th>
                <th style="width: 12%;">{{ __('planning/view.communication.table.frequency') }}</th>
                <th style="width: 40px;"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($communications as $comm)
                <tr>
                    <td>{{ $comm->communication_title }}</td>
                    <td>{{ $comm->communication_information }}</td>
                    <td>{{ optional($comm->channel)->communication_channel }}</td>
                    <td>{{ optional($comm->frequency)->communication_frequency }}</td>
                    <td class="text-center">
                        <button class="btn btn-xs btn-link text-dark p-0 me-1" onclick="editEvent({{ $comm->communication_id }})">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <form action="{{ route('projects.communication.destroy', ['project' => $project->project_id, 'communication' => $comm->communication_id]) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="askConfirmation(event, '{{ __('planning/view.quality.confirm.delete') }}')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs text-danger border-0 bg-transparent p-0"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-3 small">
                        Nenhum plano de comunicação definido.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="communicationEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="eventForm" action="{{ route('projects.communication.store', $project->project_id) }}" method="POST" onsubmit="submitAjaxForm(event)">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark">{{ __('planning/view.communication.modals.event_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-white">
                    @php $labelStyle = 'color: #000; font-weight: bold; font-size: 0.9rem;'; @endphp

                    <div class="mb-3 row border-bottom pb-2">
                        <label class="col-sm-3 col-form-label" style="{{ $labelStyle }}">
                            {{ __('planning/view.communication.modals.label_title') }}
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="communication_title" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3 row border-bottom pb-2">
                        <label class="col-sm-3 col-form-label" style="{{ $labelStyle }}">
                            {{ __('planning/view.communication.modals.label_info') }}
                        </label>
                        <div class="col-sm-9">
                            <textarea name="communication_information" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="mb-3 row border-bottom pb-2">
                        <label class="col-sm-3 col-form-label" style="{{ $labelStyle }}">
                            {{ __('planning/view.communication.modals.label_issuer') }}
                        </label>
                        <div class="col-sm-9">
                            <select name="issuers[]" class="form-select" multiple size="3">
                                <option value="" disabled style="font-style: italic; color: #999;">
                                    {{ __('planning/view.communication.modals.add_option') }}
                                </option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted small">Segure <b>Ctrl</b> para selecionar múltiplos.</div>
                        </div>
                    </div>

                    <div class="mb-3 row border-bottom pb-2">
                        <label class="col-sm-3 col-form-label" style="{{ $labelStyle }}">
                            {{ __('planning/view.communication.modals.label_receptor') }}
                        </label>
                        <div class="col-sm-9">
                            <select name="receptors[]" class="form-select" multiple size="3">
                                <option value="" disabled style="font-style: italic; color: #999;">
                                    {{ __('planning/view.communication.modals.add_option') }}
                                </option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row border-bottom pb-2">
                        <label class="col-sm-3 col-form-label" style="{{ $labelStyle }}">
                            {{ __('planning/view.communication.modals.label_channel') }}
                        </label>
                        <div class="col-sm-9">
                            <select name="communication_channel_id" class="form-select">
                                <option value="">{{ __('planning/view.communication.modals.select_option') }}</option>
                                @foreach($channels as $ch)
                                    <option value="{{ $ch->communication_channel_id }}">{{ $ch->communication_channel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row border-bottom pb-2">
                        <label class="col-sm-3 col-form-label" style="{{ $labelStyle }}">
                            {{ __('planning/view.communication.modals.label_frequency') }}
                        </label>
                        <div class="col-sm-9">
                            <select name="communication_frequency_id" class="form-select">
                                <option value="">{{ __('planning/view.communication.modals.select_option') }}</option>
                                @foreach($frequencies as $freq)
                                    <option value="{{ $freq->communication_frequency_id }}">{{ $freq->communication_frequency }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row border-bottom pb-2">
                        <label class="col-sm-3 col-form-label" style="{{ $labelStyle }}">
                            {{ __('planning/view.communication.modals.label_restrictions') }}
                        </label>
                        <div class="col-sm-9">
                            <textarea name="communication_restrictions" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="col-sm-3 col-form-label" style="{{ $labelStyle }}">
                            {{ __('planning/view.communication.modals.label_responsible') }}
                            <div class="fw-normal text-muted" style="font-size: 0.7em;">
                                {{ __('planning/view.communication.modals.label_responsible_help') }}
                            </div>
                        </label>
                        <div class="col-sm-9">
                            <select name="communication_responsible_authorization" class="form-select">
                                <option value="">{{ __('planning/view.communication.modals.select_option') }}</option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary">{{ __('planning/view.communication.modals.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="communicationChannelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark">{{ __('planning/view.communication.modals.channel_manage_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-white">
                @php $labelStyle = 'color: #000; font-weight: bold; font-size: 0.9rem;'; @endphp

                <form action="{{ route('projects.communication.store_channel', $project->project_id) }}" method="POST" onsubmit="submitAjaxForm(event)">
                    @csrf
                    <div class="row align-items-center mb-3 border-bottom pb-3">
                        <div class="col-sm-3 text-end">
                            <label class="col-form-label" style="{{ $labelStyle }}">
                                {{ __('planning/view.communication.modals.label_new_channel') }}
                            </label>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="communication_channel" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-sm-4 ps-0">
                            <button type="submit" class="btn btn-sm btn-light border shadow-sm px-2">
                                {{ __('planning/view.communication.modals.btn_send') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-light border shadow-sm px-2" data-bs-dismiss="modal">
                                {{ __('planning/view.communication.modals.btn_cancel') }}
                            </button>
                        </div>
                    </div>
                </form>

                <form action="{{ route('projects.communication.destroy_channel', $project->project_id) }}"
                      method="POST"
                      onsubmit="askConfirmation(event, '{{ __('planning/view.quality.confirm.delete') }}')">
                    @csrf
                    <div class="row align-items-center mb-3">
                        <div class="col-sm-3 text-end">
                            <label class="col-form-label" style="{{ $labelStyle }}">
                                {{ __('planning/view.communication.modals.label_channel_list') }}
                            </label>
                        </div>
                        <div class="col-sm-5">
                            <select name="delete_channel_id" class="form-select form-select-sm" required>
                                <option value="" selected disabled>{{ __('planning/view.communication.modals.select_option') }}</option>
                                @foreach($channels as $ch)
                                    <option value="{{ $ch->communication_channel_id }}">{{ $ch->communication_channel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1 ps-0">
                            <button type="submit" class="btn btn-sm btn-light border shadow-sm text-danger fw-bold" style="padding: 2px 8px;">
                                X
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="communicationFrequencyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark">{{ __('planning/view.communication.modals.frequency_manage_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-white">
                @php $labelStyle = 'color: #000; font-weight: bold; font-size: 0.9rem;'; @endphp

                <form action="{{ route('projects.communication.store_frequency', $project->project_id) }}" method="POST" onsubmit="submitAjaxForm(event)">
                    @csrf
                    <div class="row mb-1">
                        <div class="col-sm-3 text-end pt-1">
                            <label class="col-form-label" style="{{ $labelStyle }}">
                                {{ __('planning/view.communication.modals.label_new_frequency') }}
                            </label>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="communication_frequency" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-sm-4 ps-0">
                            <button type="submit" class="btn btn-sm btn-light border shadow-sm px-2">
                                {{ __('planning/view.communication.modals.btn_send') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-light border shadow-sm px-2" data-bs-dismiss="modal">
                                {{ __('planning/view.communication.modals.btn_cancel') }}
                            </button>
                        </div>
                    </div>

                    <div class="row mb-3 border-bottom pb-3">
                        <div class="col-sm-5 offset-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="communication_frequency_hasdate" id="freqCheckDate">
                                <label class="form-check-label small" for="freqCheckDate" style="{{ $labelStyle }}">
                                    {{ __('planning/view.communication.modals.label_show_date_field') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="{{ route('projects.communication.destroy_frequency', $project->project_id) }}"
                      method="POST"
                      onsubmit="askConfirmation(event, '{{ __('planning/view.quality.confirm.delete') }}')">
                    @csrf
                    <div class="row align-items-center mb-3">
                        <div class="col-sm-3 text-end">
                            <label class="col-form-label" style="{{ $labelStyle }}">
                                {{ __('planning/view.communication.modals.label_frequency_list') }}
                            </label>
                        </div>
                        <div class="col-sm-5">
                            <select name="delete_frequency_id" class="form-select form-select-sm" required>
                                <option value="" selected disabled>{{ __('planning/view.communication.modals.select_option') }}</option>
                                @foreach($frequencies as $freq)
                                    <option value="{{ $freq->communication_frequency_id }}">{{ $freq->communication_frequency }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1 ps-0">
                            <button type="submit" class="btn btn-sm btn-light border shadow-sm text-danger fw-bold" style="padding: 2px 8px;">
                                X
                            </button>
                        </div>
                    </div>
                </form>
            </div>
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
    const storeRoute = "{{ route('projects.communication.store', $project->project_id) }}";
    const updateRouteBase = "{{ url('projects/' . $project->project_id . '/communication/event') }}";

    function openCommunicationModal() {
        const form = document.getElementById('eventForm');
        form.reset();
        form.action = storeRoute;
        document.getElementById('formMethod').value = 'POST';

        resetMultiSelect(form.querySelector('[name="issuers[]"]'));
        resetMultiSelect(form.querySelector('[name="receptors[]"]'));

        new bootstrap.Modal(document.getElementById('communicationEventModal')).show();
    }

    function editEvent(id) {
        const url = `${updateRouteBase}/${id}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const form = document.getElementById('eventForm');

                form.action = url;
                document.getElementById('formMethod').value = 'PUT';

                form.querySelector('[name="communication_title"]').value = data.communication_title;
                form.querySelector('[name="communication_information"]').value = data.communication_information || '';
                form.querySelector('[name="communication_restrictions"]').value = data.communication_restrictions || '';

                setSelectValue(form, 'communication_channel_id', data.communication_channel_id);
                setSelectValue(form, 'communication_frequency_id', data.communication_frequency_id);
                setSelectValue(form, 'communication_responsible_authorization', data.communication_responsible_authorization);

                setMultiSelect(form.querySelector('[name="issuers[]"]'), data.issuer_ids);
                setMultiSelect(form.querySelector('[name="receptors[]"]'), data.receptor_ids);

                new bootstrap.Modal(document.getElementById('communicationEventModal')).show();
            })
            .catch(error => console.error('Erro ao carregar dados:', error));
    }

    function setSelectValue(form, name, value) {
        const select = form.querySelector(`[name="${name}"]`);
        if(select) select.value = value || "";
    }

    function setMultiSelect(selectElement, selectedIds) {
        if(!selectElement || !selectedIds) return;
        const ids = selectedIds.map(String);
        for (let i = 0; i < selectElement.options.length; i++) {
            const option = selectElement.options[i];
            option.selected = ids.includes(option.value);
        }
    }

    function resetMultiSelect(selectElement) {
        if(!selectElement) return;
        for (let i = 0; i < selectElement.options.length; i++) {
            selectElement.options[i].selected = false;
        }
    }

    function openChannelModal() {
        new bootstrap.Modal(document.getElementById('communicationChannelModal')).show();
    }

    function openFrequencyModal() {
        new bootstrap.Modal(document.getElementById('communicationFrequencyModal')).show();
    }

    let pendingForm = null;

    function askConfirmation(event, message) {
        event.preventDefault();
        pendingForm = event.target;
        document.getElementById('confirmationMessage').innerText = message;
        new bootstrap.Modal(document.getElementById('confirmationModal')).show();
    }

    function executePendingAction() {
        if (pendingForm) {
            const modalEl = document.getElementById('confirmationModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            processAjaxForm(pendingForm);
        }
    }

    function submitAjaxForm(event) {
        event.preventDefault();
        processAjaxForm(event.target);
    }

    function processAjaxForm(form) {
        const url = form.action;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');

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
                const data = await response.json();

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
            const instance = bootstrap.Modal.getInstance(el);
            if (instance) instance.hide();
        });
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
    }

    function reloadTab() {
        const tabBtn = document.querySelector('a[onclick*="communication"], button[onclick*="communication"]');
        if (tabBtn) {
            tabBtn.click();
            return;
        }
        if (typeof loadTab === 'function') {
            const fakeEvent = {
                preventDefault: function () {
                }, target: document.body
            };
            loadTab(fakeEvent, 'communication');
        }
    }
</script>
