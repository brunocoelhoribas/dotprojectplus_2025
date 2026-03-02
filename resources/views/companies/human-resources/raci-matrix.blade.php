@php
    use App\Models\HumanResource\HumanResourceRaci;
    use Illuminate\Support\Facades\DB;

    $companyProjects = DB::table('dotp_projects')
                        ->where('project_company', $company->company_id)
                        ->orderBy('project_name')
                        ->get();

    $projectIds = $companyProjects->pluck('project_id')->toArray();

    $companyTasks = DB::table('dotp_tasks')
                        ->whereIn('task_project', $projectIds)
                        ->orderBy('task_name')
                        ->get();

    $configuredHrs = $users->whereNotNull('humanResource');
    $hrIds = $configuredHrs->pluck('humanResource.human_resource_id')->toArray();

    $allRaci = HumanResourceRaci::whereIn('human_resource_id', $hrIds)->get();

    $groupedRaci = [];
    foreach($companyProjects as $proj) {
        $groupedRaci[$proj->project_id] = [
            'project_name' => $proj->project_name,
            'activities' => []
        ];
    }

    foreach($companyTasks as $task) {
        if(isset($groupedRaci[$task->task_project])) {
            $groupedRaci[$task->task_project]['activities'][$task->task_name] = [];
        }
    }

    foreach($allRaci as $r) {
        if(!isset($groupedRaci[$r->project_id])) continue;

        if(!isset($groupedRaci[$r->project_id]['activities'][$r->activity_name])) {
            $groupedRaci[$r->project_id]['activities'][$r->activity_name] = [];
        }

        $groupedRaci[$r->project_id]['activities'][$r->activity_name][$r->human_resource_id] = [
            'id' => $r->id,
            'role' => $r->raci_role
        ];
    }
@endphp

<div class="card shadow-sm border-0 mb-4 mt-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center p-3">
        <h5 class="mb-0 fw-bold text-dark">
            <i class="bi bi-grid-3x3 me-2"></i>{{ __('companies/view.hr.raci.title') ?? 'Matriz RACI' }}
        </h5>
        <button class="btn btn-primary btn-sm" onclick="openNewRaciModal()">
            <i class="bi bi-plus-lg"></i> {{ __('companies/view.hr.raci.add_btn') ?? 'Atribuir Papel' }}
        </button>
    </div>

    <div class="card-body p-4 overflow-auto" id="raci-matrix-container">
        @if($configuredHrs->isEmpty())
            <div class="text-center text-muted py-4">{{ __('companies/view.hr.raci.no_hr_configured') ?? 'Nenhum recurso configurado.' }}</div>
        @else
            @php $hasAnyRaci = false; @endphp

            @foreach($groupedRaci as $projId => $projData)
                {{-- Só renderiza a tabela do projeto se ele tiver tarefas ou RACI --}}
                @if(!empty($projData['activities']))
                    @php $hasAnyRaci = true; @endphp

                    <h6 class="fw-bold text-secondary mt-2 mb-3">
                        <i class="bi bi-folder me-1"></i>{{ $projData['project_name'] }}
                    </h6>

                    <table class="table table-borderless text-center align-middle raci-table mb-5" style="min-width: 600px;">
                        <thead class="border-bottom">
                        <tr>
                            <th class="text-start text-muted fw-bold pb-3" style="width: 250px;">{{ __('companies/view.hr.raci.sprint_tasks') ?? 'Tarefas' }}</th>
                            @foreach($configuredHrs as $hrUser)
                                <th style="min-width: 100px; padding-bottom: 1rem;">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-person-circle text-secondary" style="font-size: 2.5rem;"></i>
                                        <span class="small fw-bold mt-2 text-dark">{{ explode(' ', $hrUser->contact->full_name ?? $hrUser->user_username)[0] }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($projData['activities'] as $activityName => $assignments)
                            <tr class="border-bottom">
                                <td class="text-start fw-bold text-dark small p-3 bg-light rounded-start" style="border: 2px solid white;">
                                    {{ $activityName }}
                                </td>
                                @foreach($configuredHrs as $hrUser)
                                    @php
                                        $hrId = $hrUser->humanResource->human_resource_id;
                                        $assignment = $assignments[$hrId] ?? null;
                                    @endphp
                                    <td class="p-2" style="border: 2px solid white;">
                                        @if($assignment)
                                            <div class="raci-cell-block raci-color-{{ $assignment['role'] }}"
                                                 title="Remover papel"
                                                 onclick="deleteRaci({{ $hrId }}, {{ $assignment['id'] }})">
                                                <span class="raci-letter">{{ $assignment['role'] }}</span>
                                                <i class="bi bi-trash-fill raci-trash"></i>
                                            </div>
                                        @else
                                            <div class="raci-cell-block raci-empty-cell"
                                                 title="Adicionar papel para esta tarefa"
                                                 onclick="openInlineRaciModal('{{ $projId }}', '{{ addslashes($activityName) }}', {{ $hrId }})">
                                                <i class="bi bi-plus-circle-fill"></i>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            @endforeach

            @if(!$hasAnyRaci)
                <div class="text-center text-muted py-4">Nenhuma tarefa ou RACI configurado para os projetos desta empresa.</div>
            @else
                <div class="d-flex gap-3 mt-2 small fw-bold justify-content-center flex-wrap px-3 py-2 bg-light rounded">
                    <span class="px-3 py-1 rounded raci-color-R">{{ __('companies/view.hr.raci.legend_r') ?? 'R: Responsável' }}</span>
                    <span class="px-3 py-1 rounded raci-color-A">{{ __('companies/view.hr.raci.legend_a') ?? 'A: Aprovador' }}</span>
                    <span class="px-3 py-1 rounded raci-color-C">{{ __('companies/view.hr.raci.legend_c') ?? 'C: Consultado' }}</span>
                    <span class="px-3 py-1 rounded raci-color-I">{{ __('companies/view.hr.raci.legend_i') ?? 'I: Informado' }}</span>
                </div>
            @endif
        @endif
    </div>
</div>

<div class="modal fade" id="addRaciModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formAddRaci" onsubmit="saveRaci(event)">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">{{ __('companies/view.hr.raci.new_raci') ?? 'Nova Atribuição' }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('companies/view.hr.costs.project') }}</label>
                        <select id="new_raci_project_id" class="form-select" required>
                            <option value="">{{ __('companies/view.hr.actions.select') }}</option>
                            @foreach($companyProjects as $proj)
                                <option value="{{ $proj->project_id }}">{{ $proj->project_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Usando um Datalist para sugerir as Tarefas cadastradas --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('companies/view.hr.raci.activity') ?? 'Tarefa/Atividade' }}</label>
                        <input type="text" id="new_raci_activity" class="form-control" required placeholder="{{ __('companies/view.hr.raci.activity_placeholder') ?? 'Ex: Design UI' }}" list="company_tasks_list">
                        <datalist id="company_tasks_list">
                            @foreach($companyTasks->pluck('task_name')->unique() as $tName)
                                <option value="{{ $tName }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('companies/view.hr.table.member') }}</label>
                        <select id="new_raci_hr_id" class="form-select" required>
                            <option value="">{{ __('companies/view.hr.raci.select_member') ?? 'Selecione...' }}</option>
                            @foreach($configuredHrs as $hrUser)
                                <option value="{{ $hrUser->humanResource->human_resource_id }}">{{ $hrUser->contact->full_name ?? $hrUser->user_username }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('companies/view.hr.raci.role') ?? 'Papel (RACI)' }}</label>
                        <select id="new_raci_role" class="form-select" required>
                            <option value="R">R - {{ __('companies/view.hr.raci.role_r') ?? 'Responsible' }}</option>
                            <option value="A">A - {{ __('companies/view.hr.raci.role_a') ?? 'Accountable' }}</option>
                            <option value="C">C - {{ __('companies/view.hr.raci.role_c') ?? 'Consulted' }}</option>
                            <option value="I">I - {{ __('companies/view.hr.raci.role_i') ?? 'Informed' }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light justify-content-center p-3">
                    <button type="submit" id="btnSaveRaci" class="btn btn-primary px-5">{{ __('companies/view.hr.actions.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="deleteRaciConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title h6 fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('companies/view.hr.messages.error_title') ?? 'Atenção' }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{ __('companies/view.hr.messages.confirm_delete_raci') ?? 'Deseja realmente remover esta atribuição da matriz?' }}
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('companies/view.hr.actions.cancel') }}</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="executeDeleteRaci()">{{ __('companies/view.hr.table.actions') ?? 'Excluir' }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    let raciModalInstance = null;
    let deleteRaciConfirmModalInstance = null;
    let raciIdToDelete = null;
    let raciHrIdToDelete = null;

    document.addEventListener('DOMContentLoaded', function () {
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

    function saveRaci(event) {
        event.preventDefault();

        if (document.activeElement) document.activeElement.blur();

        const btn = document.getElementById('btnSaveRaci');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '{{ __('companies/view.hr.actions.saving') ?? 'Salvando...' }}';

        const hrId = document.getElementById('new_raci_hr_id').value;
        const formData = new FormData();
        formData.append('project_id', document.getElementById('new_raci_project_id').value);
        formData.append('activity_name', document.getElementById('new_raci_activity').value);
        formData.append('raci_role', document.getElementById('new_raci_role').value);

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
                    window.location.reload();
                } else {
                    if (typeof showMessage === 'function') showMessage("{{ __('companies/view.hr.messages.error_title') ?? 'Erro' }}", data.message, 'error');
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
    }

    function deleteRaci(hrId, raciId) {
        raciIdToDelete = raciId;
        raciHrIdToDelete = hrId;
        if (deleteRaciConfirmModalInstance) deleteRaciConfirmModalInstance.show();
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
                    window.location.reload();
                }
            })
            .finally(() => {
                raciIdToDelete = null;
                raciHrIdToDelete = null;
            });
    }
</script>
