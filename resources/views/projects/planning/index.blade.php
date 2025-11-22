<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="h5 mb-0">Planejamento e Monitoramento (Total: {{ $wbsItems->count() }} itens)</h4>

    <div class="btn-group">
        <button class="btn btn-outline-secondary btn-sm">Sequenciar</button>
        <button class="btn btn-outline-secondary btn-sm">Dicionário EAP</button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover table-bordered table-sm align-middle">
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
            {{--
                LINHA DO ITEM DA EAP (WBS - PAI)
            --}}
            @php
                $level = $item->level;
                $padding = $level * 25;
                // Destaque visual para pacotes de trabalho
                $rowClass = 'bg-light fw-bold';
            @endphp

            <tr class="{{ $rowClass }}">
                {{-- Coluna Nome (ocupa 5 espaços) --}}
                <td colspan="5" style="padding: 0;">
                    <div class="d-flex align-items-center" style="padding: 8px; padding-left: {{ $padding + 8 }}px;">
                        <span class="me-2 text-muted">
                            @if($item->is_leaf)
                                <i class="bi bi-folder2-open"></i>
                            @else
                                <i class="bi bi-folder-fill"></i>
                            @endif
                        </span>
                        <span>
                            {{ $item->number }} - {{ $item->name }}
                        </span>
                    </div>
                </td>

                {{-- Coluna Ações (ocupa 1 espaço) - Dropdown --}}
                <td class="text-center align-middle">
                    <div class="dropdown">
                        {{-- Botão "Três pontinhos" limpo --}}
                        <button class="btn btn-link text-dark text-decoration-none p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            {{-- Só mostra "Nova Atividade" se for um pacote de trabalho (folha) --}}
                            @if($item->is_leaf)
                                <li>
                                    <a class="dropdown-item" href="#" onclick="openNewActivityModal({{ $item->id }})">
                                        <i class="bi bi-plus-lg me-2 text-success"></i>Nova Atividade
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="#" onclick="openNewWbsItemModal({{ $item->id }})">
                                    <i class="bi bi-folder-plus me-2 text-primary"></i>Novo Sub-item
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#">
                                    <i class="bi bi-trash me-2"></i>Excluir Item
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>

            {{--
                LINHAS DAS TAREFAS (ATIVIDADES - FILHOS)
            --}}
            @foreach($item->tasks as $task)
                <tr>
                    <td>
                        <div style="padding-left: {{ $padding + 40 }}px;">
                            <i class="bi bi-file-text me-1 text-primary"></i>

                            {{-- Cálculo do código hierárquico (A.1.1.a) --}}
                            @php
                                $letter = \App\Http\Controllers\PlanningController::numberToAlpha($loop->index);
                                $hierarchicalCode = "A.{$item->number}.{$letter}";
                            @endphp
                            <span class="fw-bold text-muted me-1 small">{{ $hierarchicalCode }}</span>

                            {{ $task->task_name }}
                        </div>
                    </td>
                    <td>{{ $task->task_start_date ? $task->task_start_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $task->task_end_date ? $task->task_end_date->format('d/m/Y') : '-' }}</td>
                    <td>
                        {{ $task->task_duration }}
                        {{ $task->task_duration_type == 24 ? 'dias' : 'horas' }}
                    </td>
                    <td>
                        @if($task->owner)
                            <span class="badge bg-secondary">{{ $task->owner->contact->full_name ?? 'N/A' }}</span>
                        @endif
                    </td>

                    {{-- Ações da Tarefa: Botões lado a lado --}}
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
