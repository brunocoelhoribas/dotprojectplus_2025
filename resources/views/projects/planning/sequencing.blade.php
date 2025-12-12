@extends('dashboard')
@section('title', 'Sequenciar Atividades - ' . $project->project_name)

@push('styles')
    <style>
        .gantt-container {
            height: 500px;
            width: 100%;
            overflow: auto;
            border: 1px solid #dee2e6;
            background-color: white;
        }

        .gantt .grid-header {
            height: 40px !important;
        }
        .gantt .lower-text, .gantt .upper-text {
            font-size: 10px !important;
        }
        .gantt .bar-label {
            font-size: 10px !important;
            font-weight: normal !important;
        }

        .gantt .bar-progress { fill: #007bff !important; opacity: 0.8; }
        .gantt .bar { fill: #e9ecef !important; }
    </style>
@endpush

@section('dashboard-content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="h5 mb-0">Sequenciar Atividades</h4>

            <a href="{{ route('projects.show', ['project' => $project->project_id, 'tab' => 'planning']) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="card-body">
            <div class="alert alert-info small mb-4">
                <i class="bi bi-info-circle me-1"></i>
                Defina a ordem lógica de execução. A <strong>Atividade</strong> só poderá iniciar após a conclusão de suas <strong>Predecessoras</strong>.
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 40%">Atividade</th>
                        <th style="width: 35%">Predecessora(s) Atual(is)</th>
                        <th style="width: 25%">Adicionar Predecessora</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>
                                <div class="fw-bold text-muted small">{{ $task->wbs_code }}</div>
                                {{ $task->task_name }}
                                <div class="small text-muted">
                                    {{ $task->task_start_date ? $task->task_start_date->format('d/m/Y') : 'Sem data' }}
                                </div>
                            </td>

                            <td>
                                @forelse($task->predecessors as $pred)
                                    <div class="d-flex justify-content-between align-items-center mb-1 p-2 border rounded bg-light">
                                        <small>
                                            <strong>{{ $pred->wbs_code }}</strong> {{ Str::limit($pred->task_name, 30) }}
                                        </small>

                                        <form action="{{ route('projects.sequencing.destroy', [$project->project_id, $task->task_id, $pred->task_id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 ms-2" title="Remover vínculo">
                                                <i class="bi bi-x-circle-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <span class="text-muted small fst-italic">- Nenhuma dependência -</span>
                                @endforelse
                            </td>

                            <td>
                                <form action="{{ route('projects.sequencing.store', $project) }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $task->task_id }}">

                                    <select name="predecessor_id" class="form-select form-select-sm" required>
                                        <option value="">Selecione...</option>
                                        @foreach($tasks as $candidate)
                                            @if($candidate->task_id !== $task->task_id)
                                                <option value="{{ $candidate->task_id }}">
                                                    {{ $candidate->wbs_code }} {{ Str::limit($candidate->task_name, 25) }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5 pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Visualização Gráfica (Gantt)</h5>

                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="changeGanttView('Day')">Dia</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="changeGanttView('Week')">Semana</button>
                        <button type="button" class="btn btn-outline-secondary active" onclick="changeGanttView('Month')">Mês</button>
                    </div>
                </div>

                <div class="gantt-container">
                    <svg id="gantt-chart"></svg>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let ganttChart;

        document.addEventListener('DOMContentLoaded', function() {
            loadGantt();
        });

        function loadGantt() {
            fetch("{{ route('projects.gantt.data', $project->project_id) }}")
                .then(response => response.json())
                .then(tasks => {
                    if (tasks.length === 0) {
                        document.getElementById('gantt-chart').innerHTML = '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#999">Sem tarefas com datas definidas.</text>';
                        return;
                    }

                    ganttChart = new Gantt("#gantt-chart", tasks, {
                        header_height: 50,
                        column_width: 30,
                        step: 24,
                        view_modes: ['Quarter Day', 'Half Day', 'Day', 'Week', 'Month'],
                        bar_height: 25,
                        bar_corner_radius: 3,
                        arrow_curve: 5,
                        padding: 18,
                        view_mode: 'Month',
                        date_format: 'YYYY-MM-DD',
                        language: 'ptBr',

                        on_click: function (task) {
                            console.log("Clicou na tarefa:", task);
                        },
                        on_date_change: function(task, start, end) {
                            console.log(task, start, end);
                        },
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar Gantt:', error);
                });
        }

        function changeGanttView(mode) {
            if(ganttChart) {
                ganttChart.change_view_mode(mode);
                document.querySelectorAll('.btn-group button').forEach(btn => btn.classList.remove('active'));
                event.target.classList.add('active');
            }
        }
    </script>
@endpush
