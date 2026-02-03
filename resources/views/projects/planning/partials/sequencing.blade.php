@extends('dashboard')
@section('title', __('planning/partials.sequencing.title') . ' - ' . $project->project_name)

@section('dashboard-content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <div>
                    <h4 class="h5 fw-bold text-dark mb-1">{{ __('planning/partials.sequencing.title') }}</h4>
                    <span class="text-muted small">{{ $project->project_name }}</span>
                </div>

                <a href="{{ route('projects.show', ['project' => $project->project_id, 'tab' => 'planning']) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> {{ __('planning/partials.sequencing.back_btn') }}
                </a>
            </div>

            <div class="alert alert-info border-info small mb-4 bg-light text-dark">
                <i class="bi bi-info-circle-fill me-2 text-info"></i>
                {!! __('planning/partials.sequencing.info_text') !!}
            </div>

            <div class="table-responsive mb-5">
                <table class="table table-bordered table-hover table-sm align-middle mb-0 small border-secondary" style="font-size: 0.85rem;">
                    <thead>
                    <tr class="bg-warning border-secondary text-dark">
                        <th style="width: 40%" class="py-2">{{ __('planning/partials.sequencing.table.activity') }}</th>
                        <th style="width: 35%" class="py-2">{{ __('planning/partials.sequencing.table.current_pred') }}</th>
                        <th style="width: 25%" class="py-2">{{ __('planning/partials.sequencing.table.add_pred') }}</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white border-secondary">
                    @if(isset($tasks) && $tasks->count() > 0)
                        @foreach($tasks as $task)
                            <tr>
                                <td class="py-2">
                                    <div class="fw-bold text-primary small">{{ $task->wbs_code }}</div>
                                    <span class="fw-bold text-dark">{{ $task->task_name }}</span>
                                    <div class="small text-muted mt-1">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ $task->task_start_date ? $task->task_start_date->format('d/m/Y') : __('planning/partials.sequencing.table.no_date') }}
                                    </div>
                                </td>

                                <td>
                                    @forelse($task->predecessors as $pred)
                                        <div class="d-flex justify-content-between align-items-center mb-1 p-1 px-2 border rounded bg-light">
                                            <small class="text-dark">
                                                <strong>{{ $pred->wbs_code }}</strong> - {{ Str::limit($pred->task_name, 30) }}
                                            </small>

                                            <form action="{{ route('projects.sequencing.destroy', [$project->project_id, $task->task_id, $pred->task_id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs text-danger p-0 ms-2" title="{{ __('planning/partials.sequencing.table.remove_title') }}">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <span class="text-muted small fst-italic opacity-75">
                                            {{ __('planning/partials.sequencing.table.no_dependency') }}
                                        </span>
                                    @endforelse
                                </td>

                                <td>
                                    <form action="{{ route('projects.sequencing.store', $project) }}" method="POST" class="d-flex gap-2">
                                        @csrf
                                        <input type="hidden" name="task_id" value="{{ $task->task_id }}">

                                        <select name="predecessor_id" class="form-select form-select-sm border-secondary" required>
                                            <option value="">{{ __('planning/partials.sequencing.table.select_placeholder') }}</option>
                                            @foreach($tasks as $candidate)
                                                @if($candidate->task_id !== $task->task_id)
                                                    <option value="{{ $candidate->task_id }}">
                                                        {{ $candidate->wbs_code }} - {{ Str::limit($candidate->task_name, 25) }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <button type="submit" class="btn btn-sm btn-primary shadow-sm" title="Adicionar">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                {{ __('planning/partials.sequencing.table.empty') ?? 'Nenhuma tarefa cadastrada para sequenciar.' }}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">{{ __('planning/partials.sequencing.gantt.title') }}</h5>

                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changeGanttView('Day')">{{ __('planning/partials.sequencing.gantt.day') }}</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changeGanttView('Week')">{{ __('planning/partials.sequencing.gantt.week') }}</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary active" onclick="changeGanttView('Month')">{{ __('planning/partials.sequencing.gantt.month') }}</button>
                </div>
            </div>

            <div class="gantt-container shadow-inner">
                <svg id="gantt-chart"></svg>
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
            const chartElement = document.getElementById('gantt-chart');

            if(!chartElement) return;

            fetch("{{ route('projects.gantt.data', $project->project_id) }}")
                .then(response => response.json())
                .then(tasks => {
                    if (tasks.length === 0) {
                        chartElement.innerHTML = '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#999">{{ __('planning/partials.sequencing.gantt.no_tasks') }}</text>';
                        return;
                    }

                    const localeMap = {
                        'pt_BR': 'ptBr',
                        'es': 'es',
                        'en': 'en'
                    };
                    const currentLocale = '{{ app()->getLocale() }}';
                    const ganttLang = localeMap[currentLocale] || 'en';

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
                        language: ganttLang,
                        custom_popup_html: function(task) {
                            return `
                                <div class="p-2 small">
                                    <div class="fw-bold">${task.name}</div>
                                    <div class="text-muted">${task.start} - ${task.end}</div>
                                    <div>${task.progress}% concluído</div>
                                </div>
                            `;
                        },
                        on_click: function (task) {
                            console.log("Tarefa clicada:", task);
                        },
                        on_date_change: function(task, start, end) {
                            console.log("Mudança de data:", task, start, end);
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
                // Atualiza classe active nos botões
                document.querySelectorAll('.btn-group button').forEach(btn => btn.classList.remove('active'));
                event.target.classList.add('active');
            }
        }
    </script>
@endpush
