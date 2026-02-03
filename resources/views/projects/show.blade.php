@extends('dashboard')
@section('title', $project->project_name . ' - dotProject+')

@section('dashboard-content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
                <div>
                    <h1 class="h4 fw-bold mb-1 text-dark">{{ $project->project_name }}</h1>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                        {{ $statuses[$project->project_status] ?? 'N/A' }}
                    </span>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil-square me-1"></i> {{ __('projects/views.show.edit') }}
                    </a>
                </div>
            </div>

            <div class="bg-light p-3 rounded border mb-4 small">
                <div class="row g-3">
                    <div class="col-md-4 border-end-md">
                        <div class="mb-2">
                            <span class="text-muted fw-bold d-block">{{ __('projects/views.show.details.company') }}</span>
                            <span class="text-dark">{{ $project->company->company_name ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-0">
                            <span class="text-muted fw-bold d-block">{{ __('projects/views.show.details.owner') }}</span>
                            <span class="text-dark">{{ $project->owner->contact->full_name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-md-4 border-end-md">
                        <div class="mb-2">
                            <span class="text-muted fw-bold d-block">{{ __('projects/views.show.details.start_date') }}</span>
                            <span class="text-dark">{{ $project->project_start_date ? $project->project_start_date->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div class="mb-0">
                            <span class="text-muted fw-bold d-block">{{ __('projects/views.show.details.hours') }}</span>
                            <span class="text-dark">{{ $totalHours }}</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-2">
                            <span class="text-muted fw-bold d-block">{{ __('projects/views.show.details.end_date') }}</span>
                            <span class="text-dark">{{ $project->project_end_date ? $project->project_end_date->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block">{{ __('projects/views.show.details.priority') }}</span>
                                <span class="text-dark">{{ $priorities[$project->project_priority] ?? 'N/A' }}</span>
                            </div>
                            <div class="text-end">
                                <span class="text-muted fw-bold d-block">{{ __('projects/views.show.details.budget') }}</span>
                                <span class="text-success fw-bold">R$ {{ number_format($project->project_target_budget ?? 0, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs nav-tabs-dotproject mb-4">
                <li class="nav-item">
                    <button class="nav-link active" id="initiation-tab" data-bs-toggle="tab" data-bs-target="#initiation"
                            type="button" role="tab" aria-controls="initiation" aria-selected="true">
                        {{ __('projects/views.show.tabs.initiation') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="planning-tab" data-bs-toggle="tab" data-bs-target="#planning"
                            type="button" role="tab" aria-controls="planning" aria-selected="false">
                        {{ __('projects/views.show.tabs.planning') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="execution-tab" data-bs-toggle="tab" data-bs-target="#execution"
                            type="button" role="tab" aria-controls="execution" aria-selected="false">
                        {{ __('projects/views.show.tabs.execution') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="closing-tab" data-bs-toggle="tab" data-bs-target="#closing"
                            type="button" role="tab" aria-controls="closing" aria-selected="false">
                        {{ __('projects/views.show.tabs.closing') }}
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="projectTabContent">
                <div class="tab-pane fade show active" id="initiation" role="tabpanel" aria-labelledby="initiation-tab">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <ul class="nav nav-pills" id="initiationSubTab" role="tablist">
                            <li class="nav-item me-1" role="presentation">
                                <button class="nav-link active py-1 px-3 small" id="charter-tab" data-bs-toggle="tab" data-bs-target="#charter-content" type="button" role="tab">
                                    {{ __('projects/views.show.initiation_tabs.charter') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-1 px-3 small" id="stakeholder-tab" data-bs-toggle="tab" data-bs-target="#stakeholder-content" type="button" role="tab">
                                    {{ __('projects/views.show.initiation_tabs.stakeholder') }}
                                </button>
                            </li>
                        </ul>

                        @include('projects.partials.stakeholder_form_modal')
                    </div>

                    <div class="tab-content" id="initiationSubTabContent">
                        <div class="tab-pane fade show active" id="charter-content" role="tabpanel" aria-labelledby="charter-tab">
                            <form action="{{ route('projects.initiating', $project) }}" method="POST">
                                @csrf
                                <div class="card border border-light shadow-sm">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label for="initiating_title" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.title') }}</label>
                                                    <textarea class="form-control form-control-sm" id="initiating_title" name="initiating_title" rows="2">{{ old('initiating_title', $initiating->initiating_title) }}</textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="initiating_justification" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.justification') }}</label>
                                                    <textarea class="form-control form-control-sm" id="initiating_justification" name="initiating_justification" rows="3">{{ old('initiating_justification', $initiating->initiating_justification) }}</textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="initiating_expected_result" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.expected_results') }}</label>
                                                    <textarea class="form-control form-control-sm" id="initiating_expected_result" name="initiating_expected_result" rows="3">{{ old('initiating_expected_result', $initiating->initiating_expected_result) }}</textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="initiating_restrictions" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.restrictions') }}</label>
                                                    <textarea class="form-control form-control-sm" id="initiating_restrictions" name="initiating_restrictions" rows="2">{{ old('initiating_restrictions', $initiating->initiating_restrictions) }}</textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="initiating_milestone" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.milestones') }}</label>
                                                    <textarea class="form-control form-control-sm" id="initiating_milestone" name="initiating_milestone" rows="2">{{ old('initiating_milestone', $initiating->initiating_milestone) }}</textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 mb-2">
                                                        <label for="initiating_start_date" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.start_date') }}</label>
                                                        <input type="date" class="form-control form-control-sm" id="initiating_start_date" name="initiating_start_date" value="{{ old('initiating_start_date', $initiating->initiating_start_date) }}">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label for="initiating_end_date" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.end_date') }}</label>
                                                        <input type="date" class="form-control form-control-sm" id="initiating_end_date" name="initiating_end_date" value="{{ old('initiating_end_date', $initiating->initiating_end_date) }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label for="initiating_manager" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.manager') }}</label>
                                                    <select class="form-select form-select-sm" id="initiating_manager" name="initiating_manager">
                                                        <option value="">{{ __('planning/sequencing.table.select_placeholder') }}</option>
                                                        @foreach($users as $id => $name)
                                                            <option value="{{ $id }}" {{ old('initiating_manager', $initiating->initiating_manager) === $id ? 'selected' : '' }}>
                                                                {{ $name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="initiating_objective" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.objectives') }}</label>
                                                    <textarea class="form-control form-control-sm" id="initiating_objective" name="initiating_objective" rows="3">{{ old('initiating_objective', $initiating->initiating_objective) }}</textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="initiating_premise" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.premises') }}</label>
                                                    <textarea class="form-control form-control-sm" id="initiating_premise" name="initiating_premise" rows="3">{{ old('initiating_premise', $initiating->initiating_premise) }}</textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="initiating_success" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.success_criteria') }}</label>
                                                    <textarea class="form-control form-control-sm" id="initiating_success" name="initiating_success" rows="3">{{ old('initiating_success', $initiating->initiating_success) }}</textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="initiating_budget" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.budget') }}</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="number" step="0.01" class="form-control" id="initiating_budget" name="initiating_budget" value="{{ old('initiating_budget', $initiating->initiating_budget) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="text-muted opacity-25">

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label for="initiating_approved_comments" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.approved_comments') }}</label>
                                                    <textarea class="form-control form-control-sm bg-light" id="initiating_approved_comments" name="initiating_approved_comments" rows="2">{{ old('initiating_approved_comments', $initiating->initiating_approved_comments) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label for="initiating_authorized_comments" class="form-label small fw-bold text-muted">{{ __('projects/views.show.charter.form.authorized_comments') }}</label>
                                                    <textarea class="form-control form-control-sm bg-light" id="initiating_authorized_comments" name="initiating_authorized_comments" rows="2">{{ old('initiating_authorized_comments', $initiating->initiating_authorized_comments) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-light d-flex justify-content-end gap-2 p-2">
                                        <a href="{{ route('initiating.pdf', $project) }}" class="btn btn-sm btn-light border shadow-sm me-1" target="_blank">
                                            <i class="bi bi-file-earmark-pdf text-danger"></i> {{ __('projects/views.show.charter.form.generate_pdf') }}
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-sm px-4">
                                            <i class="bi bi-check-lg"></i> {{ __('projects/views.show.charter.form.save_draft') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="stakeholder-content" role="tabpanel" aria-labelledby="stakeholder-tab">
                            @include('projects.partials.stakeholder_list', ['project' => $project])
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="planning" role="tabpanel" aria-labelledby="planning-tab">
                    @include('projects.tabs.planning')
                </div>

                <div class="tab-pane fade" id="execution" role="tabpanel" aria-labelledby="execution-tab">
                    @include('projects.tabs.execution')
                </div>

                <div class="tab-pane fade" id="closing" role="tabpanel" aria-labelledby="closing-tab">
                    @include('projects.tabs.closing')
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.projectRoutes = {
            stakeholders: "{{ url('stakeholders') }}",
            activityStore: "{{ route('projects.activity.store', ['project' => $project->project_id, 'wbsItem' => '__ID__']) }}",
            activityUpdate: "{{ route('projects.activity.update', ['project' => $project->project_id, 'task' => '__ID__']) }}",
            activityDestroy: "{{ route('projects.activity.destroy', ['project' => $project->project_id, 'task' => '__ID__']) }}",
            wbsDestroy: "{{ route('projects.wbs.destroy', ['project' => $project->project_id, 'wbsItem' => '__ID__']) }}"
        };
    </script>
    @vite('resources/js/projects/planning.js')
@endpush
