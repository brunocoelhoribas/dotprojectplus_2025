@php use App\Http\Controllers\PlanningController; @endphp
@include('projects.planning.create_wbs_modal')
@include('projects.planning.create_activity_modal')
@include('projects.planning.edit_activity_modal')
@include('projects.planning.delete_wbs_modal')
@include('projects.planning.delete_confirmation_modal')
@include('projects.planning.partials.training_modal')
@include('projects.planning.partials.minutes_modal')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="h5 mb-0">
        Planejamento e Monitoramento
        <span class="text-muted fs-6 ms-2 fw-normal">(Total EAP: {{ $wbsItems->count() }})</span>
    </h4>

    <div class="btn-group">
        <div class="btn-group">
            <a href="{{ route('projects.sequencing.index', $project) }}" class="btn btn-outline-secondary btn-sm">
                Sequenciar atividades
            </a>
        </div>
        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#trainingModal">
            Necessidade de treinamento
        </button>
        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#minutesModal">
            Atas para reuniões de estimativas
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover table-bordered table-sm align-middle mb-0">
        <thead class="table-light">
        <tr>
            <th style="width: 50%">Atividade / EAP</th>
            <th>Início</th>
            <th>Fim</th>
            <th>Duração</th>
            <th>Recursos</th>
            <th style="width: 100px" class="text-center">Ações</th>
        </tr>
        </thead>
        <tbody>
        @forelse($wbsItems as $item)
            @php
                $level = $item->level;
                $padding = $level * 25;
                $rowClass = 'bg-light fw-bold';
                $taskCount = $item->tasks->count();
            @endphp

            <tr class="{{ $rowClass }}">
                <td colspan="5" style="padding: 0;">
                    <div class="d-flex align-items-center justify-content-between"
                         style="padding: 8px 8px 8px">

                        <div class="d-flex align-items-center">
                            <div class="d-inline-flex flex-column me-2 align-middle" style="font-size: 0.6rem;">
                                <form action="{{ route('projects.wbs.move', [$project->project_id, $item->id, 'up']) }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-decoration-none text-secondary"
                                            style="line-height: 0.8;">
                                        <i class="bi bi-caret-up-fill"></i>
                                    </button>
                                </form>
                                <form
                                    action="{{ route('projects.wbs.move', [$project->project_id, $item->id, 'down']) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-decoration-none text-secondary"
                                            style="line-height: 0.8;">
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                </form>
                            </div>

                            <span class="me-2 text-muted">
                                @if($item->is_leaf)
                                    <i class="bi bi-folder2-open"></i>
                                @else
                                    <i class="bi bi-folder-fill"></i>
                                @endif
                            </span>

                            <span>
                                {{ $item->number }} {{ $item->name }}
                            </span>

                            @if($taskCount > 0)
                                <span class="badge bg-secondary rounded-pill ms-2"
                                      style="font-size: 0.7em;">{{ $taskCount }}</span>
                            @endif
                        </div>

                        @if($taskCount > 0)
                            <div style="cursor: pointer;" onclick="toggleWbsGroup({{ $item->id }})"
                                 class="me-2 text-secondary">
                                <i class="bi bi-caret-up-fill" id="wbs-icon-{{ $item->id }}"></i>
                            </div>
                        @endif
                    </div>
                </td>

                <td class="text-center align-middle">
                    <div class="dropdown">
                        <button class="btn btn-link text-dark text-decoration-none p-0" type="button"
                                data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            @if($item->is_leaf)
                                <li>
                                    <a class="dropdown-item" href="#" onclick="openNewActivityModal({{ $item->id }})">
                                        <i class="bi bi-plus-lg me-2 text-success"></i> Nova Atividade
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="#" onclick="openNewWbsItemModal({{ $item->id }})">
                                    <i class="bi bi-folder-plus me-2 text-primary"></i> Novo Sub-item
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#"
                                   onclick="openDeleteWbsModal({{ $item->id }})">
                                    <i class="bi bi-trash me-2"></i> Excluir Item
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>

            @foreach($item->tasks as $task)
                <tr class="align-middle wbs-group-{{ $item->id }}">
                    <td>
                        <div style="padding-left: {{ $padding + 40 }}px;" class="d-flex align-items-center">

                            <div class="d-inline-flex flex-column me-2 align-middle" style="font-size: 0.6rem;">
                                <form
                                    action="{{ route('projects.activity.move', [$project->project_id, $task->task_id, 'up']) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-decoration-none text-secondary"
                                            style="line-height: 0.8;">
                                        <i class="bi bi-caret-up-fill"></i>
                                    </button>
                                </form>
                                <form
                                    action="{{ route('projects.activity.move', [$project->project_id, $task->task_id, 'down']) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-decoration-none text-secondary"
                                            style="line-height: 0.8;">
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                </form>
                            </div>

                            <a href="#" class="text-decoration-none text-dark me-2"
                               data-bs-toggle="collapse"
                               data-bs-target="#details-{{ $task->task_id }}"
                               aria-expanded="false">
                                <i class="bi bi-caret-right-fill small text-muted"></i>
                            </a>

                            <i class="bi bi-file-text me-1 text-primary"></i>

                            @php
                                /** @noinspection PhpUndefinedVariableInspection */
                                $letter = PlanningController::numberToAlpha($loop->index);
                                $hierarchicalCode = "A.$item->number.$letter";
                            @endphp
                            <span class="fw-bold text-muted me-1 small">{{ $hierarchicalCode }}</span>

                            {{ $task->task_name }}
                        </div>
                    </td>

                    <td class="text-center small">{{ $task->task_start_date ? $task->task_start_date->format('d/m/Y') : '-' }}</td>
                    <td class="text-center small">{{ $task->task_end_date ? $task->task_end_date->format('d/m/Y') : '-' }}</td>
                    <td class="text-center small">{{ $task->task_duration }} {{ $task->task_duration_type === 24 ? 'dias' : 'horas' }}</td>

                    {{-- Status Badge --}}
                    <td class="text-center">
                        @if($task->task_percent_complete === 100)
                            <span class="badge bg-success">Concluído</span>
                        @elseif($task->task_percent_complete > 0)
                            <span class="badge bg-warning text-dark">{{ $task->task_percent_complete }}%</span>
                        @else
                            <span class="badge bg-secondary">Não Iniciada</span>
                        @endif
                    </td>

                    <td class="text-center align-middle">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-outline-primary border-0 p-1"
                                    title="Editar"
                                    onclick="openEditActivityModal({{ $task }})">
                                <i class="bi bi-pencil-square fs-6"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger border-0 p-1"
                                    title="Excluir"
                                    onclick="deleteActivity({{ $task->task_id }})">
                                <i class="bi bi-trash fs-6"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="collapse bg-white wbs-group-{{ $item->id }}" id="details-{{ $task->task_id }}">
                    <td colspan="6" class="p-0 border-0">
                        <div class="p-3 border-bottom bg-light-subtle"
                             style="margin-left: {{ $padding + 60 }}px; border-left: 3px solid #dee2e6;">
                            <div class="row small">
                                <div class="col-md-6">
                                    <strong>Responsável:</strong>
                                    {{ $task->owner->contact->full_name ?? 'Não definido' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Esforço:</strong>
                                    @if($task->estimation)
                                        {{ $task->estimation->effort }}
                                        @switch($task->estimation->effort_unit)
                                            @case(0) Pessoas/Hora @break
                                            @case(1) Minutos @break
                                            @case(2) Dias @break
                                            @default Horas
                                        @endswitch
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach

        @empty
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <div class="mb-3">Nenhuma estrutura EAP definida.</div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createWbsModal">
                        Criar primeiro item da EAP
                    </button>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        function toggleWbsGroup(wbsId) {
            const rows = document.querySelectorAll(`.wbs-group-${wbsId}`);
            const icon = document.getElementById(`wbs-icon-${wbsId}`);

            let isHidden = false;

            rows.forEach(row => {
                if (row.classList.contains('collapse') && !row.classList.contains('show')) {
                    if (row.style.display !== 'none') {
                        row.style.display = 'none';
                    }
                } else {
                    if (row.style.display === 'none') {
                        row.style.display = '';
                        isHidden = false;
                    } else {
                        row.style.display = 'none';
                        isHidden = true;
                    }
                }
            });

            if (isHidden) {
                icon.classList.remove('bi-caret-up-fill');
                icon.classList.add('bi-caret-down-fill');
            } else {
                icon.classList.remove('bi-caret-down-fill');
                icon.classList.add('bi-caret-up-fill');
            }
        }
    </script>
@endpush
