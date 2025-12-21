@extends('dashboard')
@section('title', $project->project_name . ' - dotProject+')

@section('dashboard-content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('projects/views.title') }}: {{ $project->project_name }}
            </h2>
            <div>
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary btn-sm">
                    {{ __('projects/views.show.edit') }}
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    {{ __('projects/views.show.report') }}
                </a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('{{ __('projects/views.show.confirm_delete') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        {{ __('projects/views.show.delete') }}
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-4"><dl class="row">
                        <dt class="col-sm-4 text-muted">{{ __('projects/views.show.details.name') }}</dt>
                        <dd class="col-sm-8">{{ $project->project_name }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('projects/views.show.details.company') }}</dt>
                        <dd class="col-sm-8">{{ $project->company->company_name ?? 'N/A' }}</dd>
                        <dt class="col-sm-4 text-muted">{{ __('projects/views.show.details.owner') }}</dt>
                        <dd class="col-sm-8">{{ $project->owner->contact->full_name ?? 'N/A' }}</dd>
                    </dl></div>
                <div class="col-md-4"><dl class="row">
                        <dt class="col-sm-5 text-muted">{{ __('projects/views.show.details.start_date') }}</dt>
                        <dd class="col-sm-7">{{ $project->project_start_date ? $project->project_start_date->format('d/m/Y') : '-' }}</dd>
                        <dt class="col-sm-5 text-muted">{{ __('projects/views.show.details.status') }}</dt>
                        <dd class="col-sm-7">{{ $statuses[$project->project_status] ?? 'N/A' }}</dd>
                        <dt class="col-sm-5 text-muted">{{ __('projects/views.show.details.hours') }}</dt>
                        <dd class="col-sm-7">{{ $totalHours }}</dd>
                    </dl></div>
                <div class="col-md-4"><dl class="row">
                        <dt class="col-sm-6 text-muted">{{ __('projects/views.show.details.end_date') }}</dt>
                        <dd class="col-sm-6">{{ $project->project_end_date ? $project->project_end_date->format('d/m/Y') : '-' }}</dd>
                        <dt class="col-sm-6 text-muted">{{ __('projects/views.show.details.priority') }}</dt>
                        <dd class="col-sm-6">{{ $priorities[$project->project_priority] ?? 'N/A' }}</dd>
                        <dt class="col-sm-6 text-muted">{{ __('projects/views.show.details.budget') }}</dt>
                        <dd class="col-sm-6">R$ {{ number_format($project->project_target_budget ?? 0, 2, ',', '.') }}</dd>
                    </dl></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body p-4">

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
                    <ul class="nav nav-pills mb-3" id="initiationSubTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="charter-tab" data-bs-toggle="tab" data-bs-target="#charter-content" type="button" role="tab" aria-controls="charter-content" aria-selected="true">
                                {{ __('projects/views.show.initiation_tabs.charter') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="stakeholder-tab" data-bs-toggle="tab" data-bs-target="#stakeholder-content" type="button" role="tab" aria-controls="stakeholder-content" aria-selected="false">
                                {{ __('projects/views.show.initiation_tabs.stakeholder') }}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="initiationSubTabContent">
                        <div class="tab-pane fade show active" id="charter-content" role="tabpanel" aria-labelledby="charter-tab">
                            <form action="{{ route('projects.initiating', $project) }}" method="POST">
                                @csrf
                                <h4 class="h5">{{ __('projects/views.show.charter.title') }}</h4>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="initiating_title" class="form-label">{{ __('projects/views.show.charter.form.title') }}</label>
                                            <textarea class="form-control" id="initiating_title" name="initiating_title" rows="3">{{ old('initiating_title', $initiating->initiating_title) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="initiating_justification" class="form-label">{{ __('projects/views.show.charter.form.justification') }}</label>
                                            <textarea class="form-control" id="initiating_justification" name="initiating_justification" rows="3">{{ old('initiating_justification', $initiating->initiating_justification) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="initiating_expected_result" class="form-label">{{ __('projects/views.show.charter.form.expected_results') }}</label>
                                            <textarea class="form-control" id="initiating_expected_result" name="initiating_expected_result" rows="3">{{ old('initiating_expected_result', $initiating->initiating_expected_result) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="initiating_restrictions" class="form-label">{{ __('projects/views.show.charter.form.restrictions') }}</label>
                                            <textarea class="form-control" id="initiating_restrictions" name="initiating_restrictions" rows="3">{{ old('initiating_restrictions', $initiating->initiating_restrictions) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="initiating_start_date" class="form-label">{{ __('projects/views.show.charter.form.start_date') }}</label>
                                            <input type="date" class="form-control" id="initiating_start_date" name="initiating_start_date" value="{{ old('initiating_start_date', $initiating->initiating_start_date) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="initiating_milestone" class="form-label">{{ __('projects/views.show.charter.form.milestones') }}</label>
                                            <textarea class="form-control" id="initiating_milestone" name="initiating_milestone" rows="3">{{ old('initiating_milestone', $initiating->initiating_milestone) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="initiating_manager" class="form-label">{{ __('projects/views.show.charter.form.manager') }}</label>
                                            <select class="form-select" id="initiating_manager" name="initiating_manager">
                                                <option value="">{{ __('planning/sequencing.table.select_placeholder') }}</option>
                                                @foreach($users as $id => $name)
                                                    <option value="{{ $id }}" {{ old('initiating_manager', $initiating->initiating_manager) === $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="initiating_objective" class="form-label">{{ __('projects/views.show.charter.form.objectives') }}</label>
                                            <textarea class="form-control" id="initiating_objective" name="initiating_objective" rows="3">{{ old('initiating_objective', $initiating->initiating_objective) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="initiating_premise" class="form-label">{{ __('projects/views.show.charter.form.premises') }}</label>
                                            <textarea class="form-control" id="initiating_premise" name="initiating_premise" rows="3">{{ old('initiating_premise', $initiating->initiating_premise) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="initiating_budget" class="form-label">{{ __('projects/views.show.charter.form.budget') }}</label>
                                            <input type="number" step="0.01" class="form-control" id="initiating_budget" name="initiating_budget" value="{{ old('initiating_budget', $initiating->initiating_budget) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="initiating_end_date" class="form-label">{{ __('projects/views.show.charter.form.end_date') }}</label>
                                            <input type="date" class="form-control" id="initiating_end_date" name="initiating_end_date" value="{{ old('initiating_end_date', $initiating->initiating_end_date) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="initiating_success" class="form-label">{{ __('projects/views.show.charter.form.success_criteria') }}</label>
                                            <textarea class="form-control" id="initiating_success" name="initiating_success" rows="3">{{ old('initiating_success', $initiating->initiating_success) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="initiating_approved_comments" class="form-label">{{ __('projects/views.show.charter.form.approved_comments') }}</label>
                                            <textarea class="form-control" id="initiating_approved_comments" name="initiating_approved_comments" rows="3">{{ old('initiating_approved_comments', $initiating->initiating_approved_comments) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="initiating_authorized_comments" class="form-label">{{ __('projects/views.show.charter.form.authorized_comments') }}</label>
                                            <textarea class="form-control" id="initiating_authorized_comments" name="initiating_authorized_comments" rows="3">{{ old('initiating_authorized_comments', $initiating->initiating_authorized_comments) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('initiating.pdf', $project) }}" class="btn btn-outline-secondary" target="_blank">
                                        {{ __('projects/views.show.charter.form.generate_pdf') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">{{ __('projects/views.show.charter.form.save_draft') }}</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="stakeholder-content" role="tabpanel" aria-labelledby="stakeholder-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="h5 mb-0">{{ __('projects/views.show.stakeholder.title') }}</h4>

                                <div>
                                    @if($initiating->exists)
                                        <a href="{{ route('initiating.stakeholders.pdf', $initiating) }}"
                                           class="btn btn-outline-secondary btn-sm"
                                           target="_blank">
                                            {{ __('projects/views.show.stakeholder.generate_pdf') }}
                                        </a>
                                    @endif

                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#createStakeholderModal">
                                        {{ __('projects/views.show.stakeholder.new_btn') }}
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="thead-dotproject">
                                    <tr>
                                        <th>{{ __('projects/views.show.stakeholder.table.name') }}</th>
                                        <th>{{ __('projects/views.show.stakeholder.table.responsibilities') }}</th>
                                        <th>{{ __('projects/views.show.stakeholder.table.interest') }}</th>
                                        <th>{{ __('projects/views.show.stakeholder.table.power') }}</th>
                                        <th>{{ __('projects/views.show.stakeholder.table.strategy') }}</th>
                                        <th style="width: 100px;">{{ __('projects/views.show.stakeholder.table.actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($project->initiating)
                                        @forelse ($project->initiating->stakeholders as $stakeholder)
                                            <tr>
                                                <td>{{ $stakeholder->contact->full_name ?? 'N/A' }}</td>
                                                <td>{{ $stakeholder->stakeholder_responsibility }}</td>
                                                <td>{{ $stakeholder->stakeholder_interest }}</td>
                                                <td>{{ $stakeholder->stakeholder_power }}</td>
                                                <td>{{ $stakeholder->stakeholder_strategy }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                            onclick="openEditModal({{ $stakeholder }})">
                                                        {{ __('projects/views.show.stakeholder.edit') }}
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-3">
                                                    {{ __('projects/views.show.stakeholder.table.empty') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">
                                                {{ __('projects/views.show.stakeholder.table.save_charter_hint') }}
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
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

@include('projects.partials.stakeholder_form_modal')

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
