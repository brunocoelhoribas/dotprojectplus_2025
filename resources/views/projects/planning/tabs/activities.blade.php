@php
    use App\Http\Controllers\Planning\PlanningController;
@endphp

<div class="d-flex justify-content-end mb-3 gap-2">
    <a href="{{ route('projects.sequencing.index', $project->project_id) }}" class="btn btn-sm btn-outline-secondary">
        {{ __('planning/view.activities.sequencing') ?? 'Sequence Activities' }}
    </a>

    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#trainingModal">
        {{ __('planning/view.activities.training') ?? 'Training' }}
    </button>

    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#minutesModal">
        {{ __('planning/view.activities.minutes') ?? 'Minutes' }}
    </button>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm align-middle mb-0 small border-secondary" style="font-size: 0.85rem;">
        <thead>
        <tr class="bg-warning border-secondary text-dark">
            <th style="width: 50%" class="py-2">{{ __('planning/view.activities.table.wbs') }}</th>
            <th class="text-center py-2">{{ __('planning/view.activities.table.start') }}</th>
            <th class="text-center py-2">{{ __('planning/view.activities.table.end') }}</th>
            <th class="text-center py-2">{{ __('planning/view.activities.table.duration') }}</th>
            <th class="text-center py-2">{{ __('planning/view.activities.table.resources') }}</th>
            <th style="width: 80px" class="text-center py-2">{{ __('planning/view.activities.table.actions') }}</th>
        </tr>
        </thead>
        <tbody class="bg-white border-secondary">
        @forelse($wbsItems as $item)
            @php
                $level = $item->level;
                $padding = $level * 25;
                $rowClass = 'bg-light fw-bold text-dark';
                $taskCount = $item->tasks->count();
            @endphp

            <tr class="{{ $rowClass }}">
                <td colspan="5" class="p-0 border-end-0">
                    <div class="d-flex align-items-center justify-content-between p-2 ps-3">
                        <div class="d-flex align-items-center">
                            <div class="d-inline-flex flex-column me-2 align-middle" style="font-size: 0.6rem;">
                                <button type="button" onclick="moveItem('{{ route('projects.wbs.move', [$project->project_id, $item->id, 'up']) }}')" class="btn btn-link p-0 text-decoration-none text-secondary lh-1"><i class="bi bi-caret-up-fill"></i></button>
                                <button type="button" onclick="moveItem('{{ route('projects.wbs.move', [$project->project_id, $item->id, 'down']) }}')" class="btn btn-link p-0 text-decoration-none text-secondary lh-1"><i class="bi bi-caret-down-fill"></i></button>
                            </div>
                            <span class="me-2 text-primary">
                                {!! $item->is_leaf ? '<i class="bi bi-folder2-open"></i>' : '<i class="bi bi-folder-fill"></i>' !!}
                            </span>
                            <span class="text-dark">{{ $item->number }} {{ $item->name }}</span>
                            @if($taskCount > 0)
                                <span class="badge bg-secondary bg-opacity-25 text-dark border border-secondary border-opacity-25 rounded-pill ms-2" style="font-size: 0.7em;">{{ $taskCount }}</span>
                            @endif
                        </div>
                        @if($taskCount > 0)
                            <div style="cursor: pointer;" onclick="toggleWbsGroup({{ $item->id }})" class="me-2 text-secondary">
                                <i class="bi bi-caret-up-fill" id="wbs-icon-{{ $item->id }}"></i>
                            </div>
                        @endif
                    </div>
                </td>
                <td class="text-center align-middle border-start-0">
                    <div class="dropdown">
                        <button class="btn btn-xs btn-link text-dark text-decoration-none p-0" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-secondary">
                            @if($item->is_leaf)
                                <li><a class="dropdown-item small" href="#" onclick="openNewActivityModal({{ $item->id }})"><i class="bi bi-plus-lg me-2 text-success"></i> {{ __('planning/view.activities.menu.new_activity') }}</a></li>
                            @endif
                            <li><a class="dropdown-item small" href="#" onclick="openNewWbsItemModal({{ $item->id }})"><i class="bi bi-folder-plus me-2 text-primary"></i> {{ __('planning/view.activities.menu.new_subitem') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item small text-danger" href="#" onclick="openDeleteWbsModal({{ $item->id }})"><i class="bi bi-trash me-2"></i> {{ __('planning/view.activities.menu.delete_item') }}</a></li>
                        </ul>
                    </div>
                </td>
            </tr>

            @foreach($item->tasks as $task)
                <tr class="align-middle wbs-group-{{ $item->id }}">
                    <td>
                        <div style="padding-left: {{ $padding + 40 }}px;" class="d-flex align-items-center py-1">
                            <div class="d-inline-flex flex-column me-2 align-middle" style="font-size: 0.6rem;">
                                <button type="button" onclick="moveItem('{{ route('projects.activity.move', [$project->project_id, $task->task_id, 'up']) }}')" class="btn btn-link p-0 text-decoration-none text-secondary lh-1"><i class="bi bi-caret-up-fill"></i></button>
                                <button type="button" onclick="moveItem('{{ route('projects.activity.move', [$project->project_id, $task->task_id, 'down']) }}')" class="btn btn-link p-0 text-decoration-none text-secondary lh-1"><i class="bi bi-caret-down-fill"></i></button>
                            </div>
                            <a href="#" class="text-decoration-none text-dark me-2" data-bs-toggle="collapse" data-bs-target="#details-{{ $task->task_id }}" aria-expanded="false">
                                <i class="bi bi-caret-right-fill small text-muted"></i>
                            </a>
                            <i class="bi bi-file-text me-1 text-secondary opacity-75"></i>
                            @php
                                $letter = PlanningController::numberToAlpha($loop->index);
                                $hierarchicalCode = "A.$item->number.$letter";
                            @endphp
                            <span class="fw-bold text-muted me-2 small" style="font-size: 0.75rem;">{{ $hierarchicalCode }}</span>
                            <span class="text-dark">{{ $task->task_name }}</span>
                        </div>
                    </td>
                    <td class="text-center text-muted">{{ $task->task_start_date ? $task->task_start_date->format('d/m/Y') : '-' }}</td>
                    <td class="text-center text-muted">{{ $task->task_end_date ? $task->task_end_date->format('d/m/Y') : '-' }}</td>
                    <td class="text-center text-muted">
                        {{ $task->task_duration }}
                        {{ $task->task_duration_type === 24 ? __('planning/view.activities.task.days') : __('planning/view.activities.task.hours') }}
                    </td>
                    <td class="text-center">
                        @if($task->task_percent_complete === 100)
                            <span class="badge bg-success bg-opacity-75 border border-success">{{ __('planning/view.activities.task.status.completed') }}</span>
                        @elseif($task->task_percent_complete > 0)
                            <span class="badge bg-warning text-dark border border-warning">{{ $task->task_percent_complete }}%</span>
                        @else
                            <span class="badge bg-light text-dark border border-secondary">{{ __('planning/view.activities.task.status.not_started') }}</span>
                        @endif
                    </td>
                    <td class="text-center align-middle">
                        <div class="d-flex justify-content-center gap-1">
                            <button class="btn btn-xs btn-link text-primary p-0" title="{{ __('planning/view.activities.task.actions.edit') }}" onclick="openEditActivityModal({{ $task }})">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-xs btn-link text-danger p-0" title="{{ __('planning/view.activities.task.actions.delete') }}" onclick="deleteActivity({{ $task->task_id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="collapse bg-white wbs-group-{{ $item->id }}" id="details-{{ $task->task_id }}">
                    <td colspan="6" class="p-0 border-0">
                        <div class="p-2 border-bottom bg-light bg-opacity-50" style="margin-left: {{ $padding + 60 }}px; border-left: 3px solid #dee2e6;">
                            <div class="row small text-muted g-2">
                                <div class="col-md-6"><strong class="text-dark">{{ __('planning/view.activities.task.details.owner') }}:</strong> {{ $task->owner->contact->full_name ?? __('planning/view.activities.task.details.not_defined') }}</div>
                                <div class="col-md-6"><strong class="text-dark">{{ __('planning/view.activities.task.details.effort') }}:</strong> {{ $task->estimation->effort ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-list-task display-6 mb-2 opacity-50"></i>
                        <p class="mb-3">{{ __('planning/view.activities.empty.message') }}</p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createWbsModal">
                            <i class="bi bi-plus-lg me-1"></i> {{ __('planning/view.activities.empty.btn') }}
                        </button>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
