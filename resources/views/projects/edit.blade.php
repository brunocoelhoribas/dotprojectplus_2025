@extends('dashboard')

@section('title', __('projects/views.edit.page_title', ['name' => $project->project_name]))

@section('dashboard-content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="mb-0">{{ __('projects/views.edit.title') }}</h3>
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('projects.update', $project) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="project_name" class="form-label">{{ __('projects/views.form.name') }} *</label>
                                        <input type="text" class="form-control" id="project_name" name="project_name"
                                               value="{{ old('project_name', $project->project_name) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_company" class="form-label">{{ __('projects/views.form.company') }} *</label>
                                        <select class="form-select" id="project_company" name="project_company" required>
                                            <option value="">{{ __('projects/views.form.select_company') }}</option>
                                            @foreach($companies as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_company', $project->project_company) === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_owner" class="form-label">{{ __('projects/views.form.owner') }} *</label>
                                        <select class="form-select" id="project_owner" name="project_owner" required>
                                            <option value="">{{ __('projects/views.form.select_owner') }}</option>
                                            @foreach($users as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_owner', $project->project_owner) === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_target_budget" class="form-label">{{ __('projects/views.form.target_budget') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" step="0.01" class="form-control" id="project_target_budget"
                                                   name="project_target_budget" value="{{ old('project_target_budget', $project->project_target_budget) }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Coluna da Direita --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="project_start_date" class="form-label">{{ __('projects/views.form.start_date') }}</label>
                                        <input type="date" class="form-control" id="project_start_date"
                                               name="project_start_date" value="{{ old('project_start_date', optional($project->project_start_date)->format('Y-m-d')) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_end_date" class="form-label">{{ __('projects/views.form.end_date') }}</label>
                                        <input type="date" class="form-control" id="project_end_date"
                                               name="project_end_date" value="{{ old('project_end_date', optional($project->project_end_date)->format('Y-m-d')) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_status" class="form-label">{{ __('projects/views.form.status') }} *</label>
                                        <select class="form-select" id="project_status" name="project_status" required>
                                            @foreach($statuses as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_status', $project->project_status) === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_priority" class="form-label">{{ __('projects/views.form.priority') }} *</label>
                                        <select class="form-select" id="project_priority" name="project_priority" required>
                                            @foreach($priorities as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_priority', $project->project_priority) === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="project_description" class="form-label">{{ __('projects/views.form.description') }}</label>
                                        <textarea class="form-control" id="project_description" name="project_description"
                                                  rows="4">{{ old('project_description', $project->project_description) }}</textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="project_departments" id="project_departments" value="{{ old('project_departments') }}">
                                <input type="hidden" name="project_contacts" id="project_contacts" value="{{ old('project_contacts') }}">
                            </div>

                            <hr>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('projects.index') }}" class="btn btn-secondary me-3">{{ __('projects/views.form.cancel') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('projects/views.form.update') }}</button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
