@php use Carbon\Carbon; @endphp
<div class="container-fluid p-0">

    <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-sm btn-light border shadow-sm" onclick="openBudgetModal()">
            {{ __('planning/view.cost.btn_budget') }}
        </button>
    </div>

    <div class="alert alert-secondary p-3 mb-3 small border" style="background-color: #e2e3e5; color: #383d41;">
        <p class="mb-1"><strong>{{ __('planning/view.cost.info.intro') }}</strong></p>
        <ul class="list-unstyled mb-0 ms-2">
            <li>{{ __('planning/view.cost.info.a') }}</li>
            <li>{{ __('planning/view.cost.info.b') }}</li>
            <li>{{ __('planning/view.cost.info.c') }}</li>
            <li>{{ __('planning/view.cost.info.d') }}</li>
            <li>{{ __('planning/view.cost.info.e') }}</li>
        </ul>
    </div>

    <div class="d-flex justify-content-end mb-1">
        <button type="button" class="btn btn-sm btn-light border shadow-sm">
            {{ __('planning/view.cost.btn_config_hr') }}
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0 small border-secondary"
               style="font-size: 0.85rem;">
            <thead class="table-light">
            <tr class="bg-warning border-secondary">
                <th colspan="6" class="text-center text-dark fw-bold py-1">
                    {{ __('planning/view.cost.hr_title') }}
                </th>
            </tr>
            <tr class="bg-warning text-center align-middle border-secondary text-dark">
                <th width="30%">{{ __('planning/view.cost.table.name') }}</th>
                <th width="12%">{{ __('planning/view.cost.table.start') }}</th>
                <th width="12%">{{ __('planning/view.cost.table.end') }}</th>
                <th width="10%">{{ __('planning/view.cost.table.qty_hr') }}</th>
                <th width="15%">{{ __('planning/view.cost.table.unit_value_hr') }}</th>
                <th width="21%">{{ __('planning/view.cost.table.total') }}</th>
                <th width="6%"></th>
            </tr>
            </thead>
            <tbody class="bg-white border-secondary">
            @forelse($hrCosts as $cost)
                <tr>
                    <td class="text-dark">
                        {{ $cost->cost_description }}
                    </td>
                    <td class="text-center">{{ $cost->cost_date_begin ? $cost->cost_date_begin->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $cost->cost_date_end ? $cost->cost_date_end->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $cost->cost_quantity }}</td>
                    <td class="text-end pe-3">{{ number_format($cost->cost_value_unitary, 2, ',', '.') }}</td>
                    <td class="text-end pe-3">{{ number_format($cost->cost_value_total, 2, ',', '.') }}</td>
                    <td class="text-center">
                        <button class="btn btn-xs btn-link text-dark p-0 me-1"
                                onclick='openCostModal(@json($cost))'
                                title="{{ __('projects/views.show.edit') }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-2">
                        {{ __('planning/view.cost.table.empty_hr') }}
                    </td>
                </tr>
            @endforelse
            </tbody>
            <tfoot class="bg-light border-secondary">
            <tr>
                <td colspan="5" class="text-end fw-bold text-dark">{{ __('planning/view.cost.subtotal_hr') }}</td>
                <td class="text-end fw-bold text-dark pe-3">{{ number_format($totalHr, 2, ',', '.') }}</td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="text-danger small mb-4 mt-1" style="font-size: 0.75rem;">
        * {{ __('planning/view.cost.footer_note') }}
    </div>

    <div class="d-flex justify-content-end mb-1">
        <button type="button" class="btn btn-sm btn-light border shadow-sm" onclick="openCostModal()">
            {{ __('planning/view.cost.btn_add_non_hr') }}
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0 small border-secondary"
               style="font-size: 0.85rem;">
            <thead class="table-light">
            <tr class="bg-warning border-secondary">
                <th colspan="7" class="text-center text-dark fw-bold py-1">
                    {{ __('planning/view.cost.non_hr_title') }}
                </th>
            </tr>
            <tr class="bg-warning text-center align-middle border-secondary text-dark">
                <th width="30%">{{ __('planning/view.cost.table.description') }}</th>
                <th width="12%">{{ __('planning/view.cost.table.start') }}</th>
                <th width="12%">{{ __('planning/view.cost.table.end') }}</th>
                <th width="10%">{{ __('planning/view.cost.table.qty_non_hr') }}</th>
                <th width="15%">{{ __('planning/view.cost.table.unit_value_non_hr') }}</th>
                <th width="15%">{{ __('planning/view.cost.table.total') }}</th>
                <th width="6%"></th>
            </tr>
            </thead>
            <tbody class="bg-white border-secondary">
            @forelse($nonHrCosts as $cost)
                <tr>
                    <td class="text-dark">
                        {{ $cost->cost_description }}
                    </td>
                    <td class="text-center">{{ $cost->cost_date_begin ? $cost->cost_date_begin->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $cost->cost_date_end ? $cost->cost_date_end->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $cost->cost_quantity }}</td>
                    <td class="text-end pe-3">{{ number_format($cost->cost_value_unitary, 2, ',', '.') }}</td>
                    <td class="text-end pe-3">{{ number_format($cost->cost_value_total, 2, ',', '.') }}</td>
                    <td class="text-center">
                        <button class="btn btn-xs btn-link text-dark p-0 me-1"
                                onclick='openCostModal(@json($cost))'
                                title="{{ __('projects/views.show.edit') }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <form
                            action="{{ route('projects.costs.destroy', ['project' => $project->project_id, 'cost' => $cost->cost_id]) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="askConfirmation(event, '{{ __('planning/view.cost.modal.confirm_delete') }}')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs text-danger border-0 bg-transparent p-0"><i
                                    class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-2">
                        {{ __('planning/view.cost.table.empty_non_hr') }}
                    </td>
                </tr>
            @endforelse
            </tbody>
            <tfoot class="bg-light border-secondary">
            <tr>
                <td colspan="5" class="text-end fw-bold text-dark">{{ __('planning/view.cost.subtotal_non_hr') }}</td>
                <td class="text-end fw-bold text-dark pe-3">{{ number_format($totalNonHr, 2, ',', '.') }}</td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="modal fade" id="costModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="costForm" action="{{ route('projects.costs.store', $project->project_id) }}" method="POST"
                  onsubmit="submitAjaxForm(event)">
                @csrf
                <input type="hidden" name="_method" id="costFormMethod" value="POST">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="costModalTitle">
                        {{ __('planning/view.cost.modal.main_title') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-white pt-2">
                    <div class="text-center mb-4">
                        <small class="text-dark">{{ __('planning/view.cost.modal.subtitle') }}</small>
                    </div>

                    @php
                        $labelClass = "col-sm-3 col-form-label text-end fw-bold small text-dark";
                        $inputColClass = "col-sm-9";
                        $projectEndDate = $project->project_end_date ? Carbon::parse($project->project_end_date)->format('d/m/Y') : '--/--/----';
                    @endphp

                    <div class="mb-2 row">
                        <label class="{{ $labelClass }}">
                            {{ __('planning/view.cost.modal.name_label') }}<span class="text-danger">*</span>:
                        </label>
                        <div class="{{ $inputColClass }}">
                            <input type="text" name="cost_description" id="cost_description"
                                   class="form-control form-control-sm" required maxlength="150">
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="{{ $labelClass }}">
                            {{ __('planning/view.cost.modal.qty_label') }}<span class="text-danger">*</span>:
                        </label>
                        <div class="{{ $inputColClass }}">
                            <input type="number" name="cost_quantity" id="cost_quantity"
                                   class="form-control form-control-sm" required min="1" value="1"
                                   oninput="calculateCostTotal()">
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="{{ $labelClass }}">
                            {{ __('planning/view.cost.modal.date_begin_label') }}<span class="text-danger">*</span>:
                        </label>
                        <div class="col-sm-4">
                            <div class="input-group input-group-sm">
                                <input type="date" name="cost_date_begin" id="cost_date_begin" class="form-control"
                                       required>
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 row align-items-center">
                        <label class="{{ $labelClass }}">
                            {{ __('planning/view.cost.modal.date_end_label') }}<span class="text-danger">*</span>:
                        </label>
                        <div class="col-sm-9">
                            <div class="d-flex align-items-center">
                                <div class="input-group input-group-sm me-2" style="max-width: 180px;">
                                    <input type="date" name="cost_date_end" id="cost_date_end" class="form-control"
                                           required>
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                </div>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    {{ __('planning/view.cost.modal.date_end_help', ['date' => $projectEndDate]) }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="{{ $labelClass }}">
                            {{ __('planning/view.cost.modal.unit_value_label') }}<span class="text-danger">*</span>:
                        </label>
                        <div class="col-sm-3">
                            <input type="number" name="cost_value_unitary" id="cost_value_unitary"
                                   class="form-control form-control-sm" required min="0" step="0.01"
                                   oninput="calculateCostTotal()">
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="{{ $labelClass }}">
                            {{ __('planning/view.cost.modal.total_value_label') }}:
                        </label>
                        <div class="col-sm-9">
                            <div class="d-flex align-items-center">
                                <input type="text" id="cost_preview_total"
                                       class="form-control form-control-sm bg-light me-2" readonly value="0,00"
                                       style="max-width: 150px;">
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    {{ __('planning/view.cost.modal.calc_rule') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-start">
                            <small class="text-danger">{{ __('planning/view.cost.modal.required_fields') }}</small>
                        </div>
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-sm btn-light border shadow-sm me-1 px-3">
                                {{ __('planning/view.cost.modal.send') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-light border shadow-sm px-3"
                                    data-bs-dismiss="modal">
                                {{ __('planning/view.cost.modal.cancel') }}
                            </button>
                        </div>
                    </div>

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
                    {{ __('planning/view.cost.modal.confirmation_title') }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p id="confirmationMessage" class="mb-0 fw-bold text-dark"></p>
                <small class="text-muted d-block mt-2">{{ __('planning/view.cost.modal.confirmation_impact') }}</small>
            </div>
            <div class="modal-footer bg-light justify-content-center p-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    {{ __('planning/view.cost.modal.confirm_no') }}
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="executePendingAction()">
                    {{ __('planning/view.cost.modal.confirm_yes') }}
                </button>
            </div>
        </div>
    </div>
</div>

<div id="budgetModalContainer"></div>

<script>
    const costsStoreRoute = "{{ route('projects.costs.store', $project->project_id) }}";

    let pendingForm = null;

    function calculateCostTotal() {
        const qtd = parseFloat(document.getElementById('cost_quantity').value) || 0;
        const unit = parseFloat(document.getElementById('cost_value_unitary').value) || 0;
        const total = qtd * unit;

        const preview = document.getElementById('cost_preview_total');
        if (preview) {
            preview.value = total.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    }

    function openCostModal(cost = null) {
        const form = document.getElementById('costForm');
        const title = document.getElementById('costModalTitle');
        const methodInput = document.getElementById('costFormMethod');

        const modalEl = document.getElementById('costModal');
        const modal = new bootstrap.Modal(modalEl);

        if (cost) {
            form.action = costsStoreRoute + '/' + cost.cost_id;
            methodInput.value = 'PUT';
            title.innerText = "{{ __('planning/view.cost.modal.edit_title') }}";

            document.getElementById('cost_description').value = cost.cost_description;
            document.getElementById('cost_quantity').value = cost.cost_quantity;
            document.getElementById('cost_value_unitary').value = cost.cost_value_unitary;
            document.getElementById('cost_date_begin').value = cost.cost_date_begin ? cost.cost_date_begin.split('T')[0] : '';
            document.getElementById('cost_date_end').value = cost.cost_date_end ? cost.cost_date_end.split('T')[0] : '';
        } else {
            form.reset();
            form.action = costsStoreRoute;
            methodInput.value = 'POST';
            title.innerText = "{{ __('planning/view.cost.modal.create_title') }}";
            document.getElementById('cost_quantity').value = 1;
        }

        calculateCostTotal();
        modal.show();
    }

    function openBudgetModal() {
        const container = document.getElementById('budgetModalContainer');
        if (!container) {
            alert('ERRO: Não encontrei a div <div id="budgetModalContainer"></div> na blade.');
            return;
        }

        const url = "{{ route('projects.costs.budget.edit', $project->project_id) }}";

        const btn = document.querySelector('button[onclick="openBudgetModal()"]');
        let originalText = '';
        if (btn) {
            originalText = btn.innerText;
            btn.innerText = 'Carregando...';
            btn.disabled = true;
        }

        console.log('Tentando carregar orçamento de:', url);

        fetch(url)
            .then(async response => {
                if (!response.ok) {
                    const text = await response.text();
                    throw new Error(`Erro ${response.status}: ${text}`);
                }
                return response.text();
            })
            .then(html => {
                container.innerHTML = '';

                container.innerHTML = `
                    <div class="modal fade" id="budgetModal" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                ${html}
                            </div>
                        </div>
                    </div>`;

                const modalEl = document.getElementById('budgetModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            })
            .catch(err => {
                console.error('Erro detalhado:', err);
                alert('Falha ao abrir orçamento:\n' + err.message.substring(0, 200));
            })
            .finally(() => {
                if (btn) {
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            });
    }

    function calcBudgetTotal(subTotal) {
        const percent = parseFloat(document.getElementById('mgmtReserveInput').value) || 0;
        const reserveVal = (subTotal * percent) / 100;
        const finalTotal = subTotal + reserveVal;

        const totalEl = document.getElementById('finalBudgetTotal');
        if(totalEl) {
            totalEl.innerText = finalTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }

    function submitBudgetForm(event) {
        event.preventDefault();

        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerText;

        let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!token) token = form.querySelector('input[name="_token"]')?.value;

        submitBtn.disabled = true;
        submitBtn.innerText = 'Salvando...';

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            }
        })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Erro ao salvar.');
                return data;
            })
            .then(data => {
                if (data.success) {
                    const modalEl = document.getElementById('budgetModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error(error);
                alert('Erro: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerText = originalText;
            });
    }

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

        let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (!token) {
            token = form.querySelector('input[name="_token"]')?.value;
        }

        if (!token) {
            console.error('CSRF Token não encontrado. Verifique a meta tag no <head>.');
            alert('Erro de segurança: Token não encontrado. Recarregue a página.');
            return;
        }

        let originalText = '';
        if (submitBtn) {
            originalText = submitBtn.innerText;
            submitBtn.innerText = '...';
            submitBtn.disabled = true;
        }

        fetch(url, {
            method: form.querySelector('input[name="_method"]')?.value || form.method || 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
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
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro AJAX:', error);
                alert('Erro: ' + error.message);
            })
            .finally(() => {
                if (submitBtn) {
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
        const tabBtn = document.querySelector('a[onclick*="costs"], button[onclick*="costs"], a[onclick*="cost"], button[onclick*="cost"]');

        if (tabBtn) {
            tabBtn.click();
            return;
        }

        if (typeof loadTab === 'function') {
            const fakeEvent = {
                preventDefault: function () {
                },
                target: document.body
            };
            loadTab(fakeEvent, 'costs');
        } else {
            window.location.reload();
        }
    }
</script>
