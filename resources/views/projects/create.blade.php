@extends('dashboard')

@section('title', __('projects/views.form.create_title'))

@section('dashboard-content')
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <h1 class="h4 fw-bold text-dark mb-0">
                                {{ __('projects/views.form.create_title') }}
                            </h1>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('projects.store') }}">
                            @csrf

                            <div class="row g-4">
                                <div class="col-md-6 border-end-md">
                                    <div class="mb-3">
                                        <label for="project_name" class="form-label small fw-bold text-muted">
                                            {{ __('projects/views.form.name') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control form-control-sm" id="project_name" name="project_name"
                                               value="{{ old('project_name') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_company" class="form-label small fw-bold text-muted">
                                            {{ __('projects/views.form.company') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-sm" id="project_company" name="project_company" required>
                                            <option value="">{{ __('projects/views.form.select_company') }}</option>
                                            @foreach($companies as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_company', $companyId) === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_owner" class="form-label small fw-bold text-muted">
                                            {{ __('projects/views.form.owner') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-sm" id="project_owner" name="project_owner" required>
                                            <option value="">{{ __('projects/views.form.select_owner') }}</option>
                                            @foreach($users as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_owner', auth()->id()) === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_target_budget" class="form-label small fw-bold text-muted">
                                            {{ __('projects/views.form.target_budget') }}
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" step="0.01" class="form-control" id="project_target_budget"
                                                   name="project_target_budget" value="{{ old('project_target_budget') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="project_start_date" class="form-label small fw-bold text-muted">
                                                {{ __('projects/views.form.start_date') }}
                                            </label>
                                            <input type="date" class="form-control form-control-sm" id="project_start_date"
                                                   name="project_start_date" value="{{ old('project_start_date') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="project_end_date" class="form-label small fw-bold text-muted">
                                                {{ __('projects/views.form.end_date') }}
                                            </label>
                                            <input type="date" class="form-control form-control-sm" id="project_end_date"
                                                   name="project_end_date" value="{{ old('project_end_date') }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_status" class="form-label small fw-bold text-muted">
                                            {{ __('projects/views.form.status') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-sm" id="project_status" name="project_status" required>
                                            @foreach($statuses as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_status') === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_priority" class="form-label small fw-bold text-muted">
                                            {{ __('projects/views.form.priority') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-sm" id="project_priority" name="project_priority" required>
                                            @foreach($priorities as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_priority') === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="project_description" class="form-label small fw-bold text-muted">
                                            {{ __('projects/views.form.description') }}
                                        </label>
                                        <textarea class="form-control form-control-sm" id="project_description" name="project_description"
                                                  rows="5">{{ old('project_description') }}</textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="project_departments" id="project_departments" value="{{ old('project_departments') }}">
                                <input type="hidden" name="project_contacts" id="project_contacts" value="{{ old('project_contacts') }}">
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm px-4">
                                    {{ __('projects/views.form.cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm px-4">
                                    <i class="bi bi-check-lg me-1"></i> {{ __('projects/views.form.save') }}
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
