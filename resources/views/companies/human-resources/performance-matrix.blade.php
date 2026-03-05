@php
    use App\Models\HumanResource\HumanResourcePerformance;

    $configuredHrs = $users->whereNotNull('humanResource');
    $hrIds = $configuredHrs->pluck('humanResource.human_resource_id')->toArray();

    // Busca as avaliações de desempenho
    $performanceData = HumanResourcePerformance::where('company_id', $company->company_id)
        ->whereIn('human_resource_id', $hrIds)
        ->get();

    $matrix9Box = [];
    for ($pot = 3; $pot >= 1; $pot--) {
        for ($perf = 1; $perf <= 3; $perf++) {
            $matrix9Box[$pot][$perf] = [];
        }
    }

    foreach ($performanceData as $eval) {
        $hrUser = $configuredHrs->firstWhere('humanResource.human_resource_id', $eval->human_resource_id);
        if ($hrUser) {
            $matrix9Box[$eval->potential_score][$eval->performance_score][] = [
                'id' => $eval->human_resource_id,
                'name' => explode(' ', $hrUser->contact->full_name ?? $hrUser->user_username)[0],
                'notes' => $eval->facilitator_notes
            ];
        }
    }

    $boxColors = [
        '3_1' => '#ffeeba',
        '3_2' => '#c3e6cb',
        '3_3' => '#28a745',
        '2_1' => '#f5c6cb',
        '2_2' => '#ffeeba',
        '2_3' => '#c3e6cb',
        '1_1' => '#dc3545',
        '1_2' => '#ffeeba',
        '1_3' => '#c3e6cb',
    ];
@endphp

<style>
    .nine-box-container {
        display: flex;
        align-items: stretch;
        margin-top: 0.5rem;
    }

    .y-axis-label {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        text-align: center;
        font-weight: bold;
        margin-right: 14px;
        color: #6c757d;
        font-size: 0.9rem;
        letter-spacing: 0.05em;
    }

    .x-axis-label {
        text-align: center;
        font-weight: bold;
        margin-top: 14px;
        color: #6c757d;
        font-size: 0.9rem;
        letter-spacing: 0.05em;
    }

    .nine-box-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: repeat(3, 180px);
        gap: 12px;
        flex-grow: 1;
    }

    .box-item {
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        position: relative;
        box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.07), 0 2px 6px rgba(0,0,0,0.06);
        overflow-y: auto;
    }

    .box-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        opacity: 0.85;
        margin-bottom: 10px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        padding-bottom: 6px;
    }

    .hr-pill {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 20px;
        padding: 5px 12px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #333;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .hr-pill:hover {
        background: #fff;
        transform: scale(1.02);
    }

    .hr-pill-delete {
        color: #dc3545;
        display: none;
    }

    .hr-pill:hover .hr-pill-delete {
        display: block;
    }
</style>

<div class="modal fade" id="performanceMatrixModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark">
                    <i
                        class="bi bi-bar-chart-line-fill me-2"></i>{{ __('companies/view.hr.performance.title') ?? 'Matriz de Desempenho e Potencial' }}
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary btn-sm" onclick="openAddPerformanceModal()">
                        <i class="bi bi-plus-lg"></i>
                        {{ __('companies/view.hr.performance.evaluate_btn') ?? 'Avaliar Recurso' }}
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body p-4 bg-white" id="performance-matrix-container">
                <div class="nine-box-container">
                    <div class="y-axis-label">{{ __('companies/view.hr.performance.axis_potential') ?? 'Potencial' }}
                        ➔
                    </div>

                    <div class="d-flex flex-column flex-grow-1">
                        <div class="nine-box-grid">
                            @for($pot = 3; $pot >= 1; $pot--)
                                @for($perf = 1; $perf <= 3; $perf++)
                                    @php $key = $pot . '_' . $perf; @endphp
                                    <div class="box-item"
                                        style="background-color: {{ $boxColors[$key] }}; color: {{ in_array($key, ['3_3', '1_1']) ? '#fff' : '#212529' }};">
                                        <div class="box-title">{{ __('companies/view.hr.performance.box_' . $key) }}</div>

                                        @forelse($matrix9Box[$pot][$perf] as $person)
                                            <div class="hr-pill" title="{{ $person['notes'] ?? 'Sem anotações' }}">
                                                <span><i class="bi bi-person-circle me-1"></i> {{ $person['name'] }}</span>
                                                <i class="bi bi-x-circle-fill hr-pill-delete"
                                                    onclick="deletePerformance({{ $person['id'] }})"></i>
                                            </div>
                                        @empty
                                            <div class="small opacity-50">
                                                {{ __('companies/view.hr.performance.empty_box') ?? 'Vazio' }}</div>
                                        @endforelse
                                    </div>
                                @endfor
                            @endfor
                        </div>
                        <div class="x-axis-label">
                            {{ __('companies/view.hr.performance.axis_performance') ?? 'Desempenho' }}
                            ➔
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePerformanceConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title h6 fw-bold"><i
                        class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('companies/view.hr.messages.error_title') ?? 'Atenção' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                {{ __('companies/view.hr.performance.confirm_delete') ?? 'Remover recurso da matriz?' }}
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-secondary btn-sm"
                    data-bs-dismiss="modal">{{ __('companies/view.hr.actions.cancel') }}</button>
                <button type="button" class="btn btn-danger btn-sm px-4"
                    onclick="executeDeletePerformance()">{{ __('companies/view.hr.table.actions') ?? 'Excluir' }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addPerformanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formAddPerformance" onsubmit="savePerformance(event)">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">
                        {{ __('companies/view.hr.performance.evaluate_btn') ?? 'Avaliar Recurso' }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('companies/view.hr.table.member') }}</label>
                        <select id="perf_hr_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach($configuredHrs as $hrUser)
                                <option value="{{ $hrUser->humanResource->human_resource_id }}">
                                    {{ $hrUser->contact->full_name ?? $hrUser->user_username }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                class="form-label small fw-bold text-primary">{{ __('companies/view.hr.performance.axis_performance') }}</label>
                            <select id="perf_score" name="performance_score" class="form-select" required>
                                <option value="1">1 - {{ __('companies/view.hr.performance.score_1') }}</option>
                                <option value="2" selected>2
                                    - {{ __('companies/view.hr.performance.score_2') }}</option>
                                <option value="3">3 - {{ __('companies/view.hr.performance.score_3') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label
                                class="form-label small fw-bold text-success">{{ __('companies/view.hr.performance.axis_potential') }}</label>
                            <select id="pot_score" name="potential_score" class="form-select" required>
                                <option value="1">1 - {{ __('companies/view.hr.performance.score_1') }}</option>
                                <option value="2" selected>2
                                    - {{ __('companies/view.hr.performance.score_2') }}</option>
                                <option value="3">3 - {{ __('companies/view.hr.performance.score_3') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('companies/view.hr.performance.notes') }}</label>
                        <textarea id="perf_notes" name="facilitator_notes" class="form-control" rows="2"
                            placeholder="Plano de desenvolvimento, feedbacks, etc..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light justify-content-center p-3">
                    <button type="submit" id="btnSavePerformance"
                        class="btn btn-primary px-5">{{ __('companies/view.hr.actions.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let perfModalInstance = null;
    let deletePerfConfirmModalInstance = null;
    let mainPerfMatrixModalInstance = null;
    let perfIdToDelete = null;

    document.addEventListener('DOMContentLoaded', function () {
        const mainPerfEl = document.getElementById('performanceMatrixModal');
        if (mainPerfEl) {
            mainPerfMatrixModalInstance = bootstrap.Modal.getOrCreateInstance(mainPerfEl);
        }

        if (document.getElementById('addPerformanceModal')) {
            perfModalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('addPerformanceModal'));
        }

        if (document.getElementById('deletePerformanceConfirmModal')) {
            deletePerfConfirmModalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('deletePerformanceConfirmModal'));
        }
    });

    function openAddPerformanceModal() {
        document.getElementById('formAddPerformance').reset();
        perfModalInstance.show();
    }

    function refreshPerformanceMatrix() {
        fetch(window.location.href)
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('performance-matrix-container').innerHTML;
                document.getElementById('performance-matrix-container').innerHTML = newContent;

                document.body.classList.add('modal-open');
                document.body.style.overflow = 'hidden';
            })
            .catch(err => console.error("Erro ao atualizar 9-Box:", err));
    }

    function savePerformance(event) {
        event.preventDefault();
        if (document.activeElement) document.activeElement.blur();

        const btn = document.getElementById('btnSavePerformance');
        const originalText = btn.innerHTML;
        btn.disabled = true;

        const formData = new FormData(document.getElementById('formAddPerformance'));
        const hrId = document.getElementById('perf_hr_id').value;
        formData.append('human_resource_id', hrId);

        let postUrl = "{{ route('companies.hr.performance.store', ['company' => $company->company_id]) }}";

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
                    perfModalInstance.hide();
                    refreshPerformanceMatrix();
                } else {
                    if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.error_title') ?? 'Erro' }}", data.message || "{{ __('companies/view.hr.messages.save_error') ?? 'Erro ao salvar' }}", 'error');
                }
            })
            .catch(err => {
                if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.error_title') ?? 'Erro' }}", err.message || "{{ __('companies/view.hr.messages.save_error') ?? 'Erro ao salvar' }}", 'error');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
    }

    function deletePerformance(hrId) {
        perfIdToDelete = hrId;
        if (deletePerfConfirmModalInstance) deletePerfConfirmModalInstance.show();
    }

    function executeDeletePerformance() {
        if (document.activeElement) document.activeElement.blur();
        if (!perfIdToDelete) return;

        let url = "{{ route('companies.hr.performance.destroy', ['company' => $company->company_id, 'hr_id' => ':hrId']) }}".replace(':hrId', perfIdToDelete);

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
                    deletePerfConfirmModalInstance.hide();
                    refreshPerformanceMatrix();
                } else {
                    if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.error_title') ?? 'Erro' }}", data.message || "{{ __('companies/view.hr.messages.save_error') ?? 'Erro ao remover' }}", 'error');
                }
            })
            .catch(err => {
                if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.error_title') ?? 'Erro' }}", err.message || "{{ __('companies/view.hr.messages.save_error') ?? 'Erro' }}", 'error');
            })
            .finally(() => {
                perfIdToDelete = null;
            });
    }
</script>
