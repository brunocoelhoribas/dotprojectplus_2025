@php
    use App\Http\Controllers\PlanningController;
@endphp

<div class="table-responsive">
    <table class="table table-hover table-bordered table-sm align-middle mb-0">
        <thead class="table-light">
        <tr>
            <th style="width: 50%">{{ __('planning/view.activities.table.wbs') }}</th>
            <th>{{ __('planning/view.activities.table.start') }}</th>
            <th>{{ __('planning/view.activities.table.end') }}</th>
            <th>{{ __('planning/view.activities.table.duration') }}</th>
            <th>{{ __('planning/view.activities.table.resources') }}</th>
            <th style="width: 100px" class="text-center">{{ __('planning/view.activities.table.actions') }}</th>
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
                    <div class="d-flex align-items-center justify-content-between" style="padding: 8px 8px 8px">
                        <div class="d-flex align-items-center">
                            <div class="d-inline-flex flex-column me-2 align-middle" style="font-size: 0.6rem;">
                                <button type="button"
                                        onclick="moveItem('{{ route('projects.wbs.move', [$project->project_id, $item->id, 'up']) }}')"
                                        class="btn btn-link p-0 text-decoration-none text-secondary"
                                        style="line-height: 0.8;">
                                    <i class="bi bi-caret-up-fill"></i>
                                </button>

                                <button type="button"
                                        onclick="moveItem('{{ route('projects.wbs.move', [$project->project_id, $item->id, 'down']) }}')"
                                        class="btn btn-link p-0 text-decoration-none text-secondary"
                                        style="line-height: 0.8;">
                                    <i class="bi bi-caret-down-fill"></i>
                                </button>
                            </div>

                            <span class="me-2 text-muted">
                                    @if($item->is_leaf) <i class="bi bi-folder2-open"></i> @else <i class="bi bi-folder-fill"></i> @endif
                                </span>
                            <span>{{ $item->number }} {{ $item->name }}</span>
                            @if($taskCount > 0)
                                <span class="badge bg-secondary rounded-pill ms-2" style="font-size: 0.7em;">{{ $taskCount }}</span>
                            @endif
                        </div>

                        @if($taskCount > 0)
                            <div style="cursor: pointer;" onclick="toggleWbsGroup({{ $item->id }})" class="me-2 text-secondary">
                                <i class="bi bi-caret-up-fill" id="wbs-icon-{{ $item->id }}"></i>
                            </div>
                        @endif
                    </div>
                </td>
                <td class="text-center align-middle">
                    <div class="dropdown">
                        <button class="btn btn-link text-dark text-decoration-none p-0" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            @if($item->is_leaf)
                                <li>
                                    <a class="dropdown-item" href="#" onclick="openNewActivityModal({{ $item->id }})">
                                        <i class="bi bi-plus-lg me-2 text-success"></i> {{ __('planning/view.activities.menu.new_activity') }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="#" onclick="openNewWbsItemModal({{ $item->id }})">
                                    <i class="bi bi-folder-plus me-2 text-primary"></i> {{ __('planning/view.activities.menu.new_subitem') }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="openDeleteWbsModal({{ $item->id }})">
                                    <i class="bi bi-trash me-2"></i> {{ __('planning/view.activities.menu.delete_item') }}
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
                                <button type="button"
                                        onclick="moveItem('{{ route('projects.activity.move', [$project->project_id, $task->task_id, 'up']) }}')"
                                        class="btn btn-link p-0 text-decoration-none text-secondary"
                                        style="line-height: 0.8;">
                                    <i class="bi bi-caret-up-fill"></i>
                                </button>

                                <button type="button"
                                        onclick="moveItem('{{ route('projects.activity.move', [$project->project_id, $task->task_id, 'down']) }}')"
                                        class="btn btn-link p-0 text-decoration-none text-secondary"
                                        style="line-height: 0.8;">
                                    <i class="bi bi-caret-down-fill"></i>
                                </button>
                            </div>

                            <a href="#" class="text-decoration-none text-dark me-2" data-bs-toggle="collapse" data-bs-target="#details-{{ $task->task_id }}" aria-expanded="false">
                                <i class="bi bi-caret-right-fill small text-muted"></i>
                            </a>
                            <i class="bi bi-file-text me-1 text-primary"></i>

                            @php
                                $letter = PlanningController::numberToAlpha($loop->index);
                                $hierarchicalCode = "A.$item->number.$letter";
                            @endphp
                            <span class="fw-bold text-muted me-1 small">{{ $hierarchicalCode }}</span>
                            {{ $task->task_name }}
                        </div>
                    </td>
                    <td class="text-center small">{{ $task->task_start_date ? $task->task_start_date->format('d/m/Y') : '-' }}</td>
                    <td class="text-center small">{{ $task->task_end_date ? $task->task_end_date->format('d/m/Y') : '-' }}</td>
                    <td class="text-center small">
                        {{ $task->task_duration }}
                        {{ $task->task_duration_type === 24 ? __('planning/view.activities.task.days') : __('planning/view.activities.task.hours') }}
                    </td>
                    <td class="text-center">
                        @if($task->task_percent_complete === 100)
                            <span class="badge bg-success">{{ __('planning/view.activities.task.status.completed') }}</span>
                        @elseif($task->task_percent_complete > 0)
                            <span class="badge bg-warning text-dark">{{ $task->task_percent_complete }}%</span>
                        @else
                            <span class="badge bg-secondary">{{ __('planning/view.activities.task.status.not_started') }}</span>
                        @endif
                    </td>
                    <td class="text-center align-middle">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-outline-primary border-0 p-1" title="{{ __('planning/view.activities.task.actions.edit') }}" onclick="openEditActivityModal({{ $task }})">
                                <i class="bi bi-pencil-square fs-6"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger border-0 p-1" title="{{ __('planning/view.activities.task.actions.delete') }}" onclick="deleteActivity({{ $task->task_id }})">
                                <i class="bi bi-trash fs-6"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="collapse bg-white wbs-group-{{ $item->id }}" id="details-{{ $task->task_id }}">
                    <td colspan="6" class="p-0 border-0">
                        <div class="p-3 border-bottom bg-light-subtle" style="margin-left: {{ $padding + 60 }}px; border-left: 3px solid #dee2e6;">
                            <div class="row small">
                                <div class="col-md-6">
                                    <strong>{{ __('planning/view.activities.task.details.owner') }}</strong>
                                    {{ $task->owner->contact->full_name ?? __('planning/view.activities.task.details.not_defined') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>{{ __('planning/view.activities.task.details.effort') }}</strong>
                                    @if($task->estimation)
                                        {{ $task->estimation->effort }}
                                        @switch($task->estimation->effort_unit)
                                            @case(0) {{ __('planning/view.activities.task.details.units.person_hour') }} @break
                                            @case(1) {{ __('planning/view.activities.task.details.units.minutes') }} @break
                                            @case(2) {{ __('planning/view.activities.task.details.units.days') }} @break
                                            @default {{ __('planning/view.activities.task.details.units.hours') }}
                                        @endswitch
                                    @else - @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <div class="mb-3">{{ __('planning/view.activities.empty.message') }}</div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createWbsModal">
                        {{ __('planning/view.activities.empty.btn') }}
                    </button>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
