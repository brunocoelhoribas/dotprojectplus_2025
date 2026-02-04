<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm align-middle mb-0 small border-secondary"
           style="font-size: 0.85rem;">
        <thead>
        <tr class="bg-light text-dark text-center">
            <th style="width: 35%" class="text-start">{{ __('execution/view.table.activity') }}</th>
            <th style="width: 12%">{{ __('execution/view.table.dates_start') }}</th>
            <th style="width: 12%">{{ __('execution/view.table.dates_end') }}</th>
            <th style="width: 10%">{{ __('execution/view.table.duration') }}</th>
            <th style="width: 15%">{{ __('execution/view.table.assigned_to') }}</th>
            <th style="width: 10%">{{ __('execution/view.table.status') }}</th>
        </tr>
        </thead>
        <tbody class="bg-white">
        @forelse($wbsItems as $item)
            @if($item->tasks->isEmpty())
                @continue
            @endif

            <tr class="bg-light fw-bold text-dark">
                <td colspan="7" class="py-2 ps-3 border-bottom-0">
                    <span class="me-2 text-secondary"><i class="bi bi-folder-fill"></i></span>
                    {{ $item->number }} {{ $item->name }}
                </td>
            </tr>

            @foreach($item->tasks as $task)
                @php
                    $planStart = $task->task_start_date ? $task->task_start_date->format('d/m/Y') : '-';
                    $planEnd   = $task->task_end_date ? $task->task_end_date->format('d/m/Y') : '-';
                    $planDur   = $task->task_duration ? $task->task_duration . ' h' : '-';

                    $logs = $task->logs;
                    $actualStart = '-';
                    $actualEnd = '-';
                    $actualDur = 0;

                    if ($logs->isNotEmpty()) {
                        $actualStart = $logs->first()->task_log_date->format('d/m/Y');

                        if ($task->task_percent_complete === 100) {
                            $actualEnd = $logs->last()->task_log_date->format('d/m/Y');
                        }

                        $actualDur = $logs->sum('task_log_hours');
                    }

                    $statusText = __('execution/view.status.not_started');
                    $statusClass = 'text-danger';

                    if ($task->task_percent_complete > 0 && $task->task_percent_complete < 100) {
                        $statusText = __('execution/view.status.working');
                        $statusClass = 'text-primary';
                    } elseif ($task->task_percent_complete === 100) {
                        $statusText = __('execution/view.status.concluded');
                        $statusClass = 'text-success';
                    }
                @endphp

                <tr>
                    <td>
                        <div style="padding-left: 20px;">
                            <div class="fw-bold text-dark">{{ $task->task_name }}</div>
                            <div class="mt-1">
                                <a href="#" onclick="openLogModal({{ $task->task_id }})"
                                   class="text-decoration-none small text-success me-3">
                                    <i class="bi bi-plus-circle"></i> {{ __('execution/view.actions.new_log') }}
                                </a>
                                @if($logs->isNotEmpty())
                                    <a href="#" onclick="openHistoryModal({{ $task->task_id }})"
                                       class="text-decoration-none small text-primary">
                                        <i class="bi bi-list-ul"></i> {{ __('execution/view.actions.view_logs') }}
                                        ({{ $logs->count() }})
                                    </a>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td class="text-center small">
                        <div class="text-muted" title="Planejado">{{ $planStart }}</div>
                        @if($actualStart !== '-')
                            <div class="text-dark fw-bold border-top mt-1 pt-1" title="Real">{{ $actualStart }}</div>
                        @endif
                    </td>

                    <td class="text-center small">
                        <div class="text-muted" title="Planejado">{{ $planEnd }}</div>
                        @if($actualEnd !== '-')
                            <div class="text-dark fw-bold border-top mt-1 pt-1" title="Real">{{ $actualEnd }}</div>
                        @endif
                    </td>

                    <td class="text-center small">
                        <div class="text-muted" title="Planejado">{{ $planDur }}</div>
                        @if($actualDur > 0)
                            <div class="text-primary fw-bold border-top mt-1 pt-1" title="Real">{{ $actualDur }} h</div>
                        @endif
                    </td>

                    <td class="small">
                        @if($task->assignees->isNotEmpty())
                            @foreach($task->assignees as $user)
                                <div class="mb-1 d-flex align-items-center">
                                    <span class="badge bg-secondary me-1"
                                          style="font-size: 0.6rem; padding: 2px 4px;"
                                          title="{{ $user->user_username }}">
                                        {{ substr($user->user_username ?? 'U', 0, 2) }}
                                    </span>

                                    <span class="text-dark">
                                        {{ $user->contact->first_name ?? $user->user_username }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            @foreach($task->estimatedRoles as $role)
                                <div class="text-danger fst-italic" style="font-size: 0.75rem">
                                    Papel não alocado
                                </div>
                            @endforeach
                        @endif
                    </td>

                    <td class="text-center">
                        <span class="badge bg-light {{ $statusClass }} border border-light-subtle">
                            {{ $statusText }}
                        </span>
                        <div class="progress mt-1" style="height: 4px;">
                            <div
                                class="progress-bar {{ $task->task_percent_complete === 100 ? 'bg-success' : 'bg-primary' }}"
                                style="width: {{ $task->task_percent_complete }}%"></div>
                        </div>
                        <div class="small text-muted">{{ $task->task_percent_complete }}%</div>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="7" class="text-center p-4 text-muted">Nenhum dado encontrado.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

