{{-- Sub-abas de Execução --}}
<ul class="nav nav-pills mb-3" id="executionSubTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="exec-tasks-tab" data-bs-toggle="tab" data-bs-target="#exec-tasks"
                type="button">Tarefas
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="exec-logs-tab" data-bs-toggle="tab" data-bs-target="#exec-logs" type="button">Logs
            de Tarefas
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="exec-depts-tab" data-bs-toggle="tab" data-bs-target="#exec-depts" type="button">
            Departamentos
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="exec-contacts-tab" data-bs-toggle="tab" data-bs-target="#exec-contacts"
                type="button">Contatos
        </button>
    </li>
</ul>

<div class="tab-content" id="executionSubTabContent">

    {{-- 1. LISTA DE TAREFAS (Execução) --}}
    <div class="tab-pane fade show active" id="exec-tasks" role="tabpanel">
        <h5 class="mb-3">Lista de Tarefas (Execução)</h5>
        <table class="table table-hover table-sm">
            <thead>
            <tr>
                <th>Tarefa</th>
                <th>Progresso</th>
                <th>Responsável</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @forelse($executionTasks as $task)
                <tr>
                    <td>{{ $task->task_name }}</td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $task->task_percent_complete }}%;">
                                {{ $task->task_percent_complete }}%
                            </div>
                        </div>
                    </td>
                    <td>{{ $task->owner->contact->full_name ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="openLogModal({{ $task->task_id }})">
                            Apontar Horas
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Nenhuma tarefa encontrada.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- 2. LOGS --}}
    <div class="tab-pane fade" id="exec-logs" role="tabpanel">
        <p class="text-muted mt-3">Histórico de apontamentos de horas (implementação futura).</p>
    </div>

    {{-- 3. DEPARTAMENTOS --}}
    <div class="tab-pane fade" id="exec-depts" role="tabpanel">
        <ul class="list-group mt-3">
            @forelse($project->departments as $dept)
                <li class="list-group-item">{{ $dept->dept_name }}</li>
            @empty
                <li class="list-group-item text-muted">Nenhum departamento vinculado.</li>
            @endforelse
        </ul>
    </div>

    {{-- 4. CONTATOS --}}
    <div class="tab-pane fade" id="exec-contacts" role="tabpanel">
        <ul class="list-group mt-3">
            @forelse($project->contacts as $contact)
                <li class="list-group-item">{{ $contact->full_name }} ({{ $contact->contact_email }})</li>
            @empty
                <li class="list-group-item text-muted">Nenhum contato vinculado.</li>
            @endforelse
        </ul>
    </div>
</div>
