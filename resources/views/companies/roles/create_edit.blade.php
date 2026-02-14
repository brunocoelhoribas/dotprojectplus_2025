@extends('dashboard')

{{-- Título da Aba do Navegador --}}
@section('title', $isEdit ? __('companies/view.roles.titles.edit') : __('companies/view.roles.titles.create'))

@section('dashboard-content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold text-dark">
                @if($isEdit)
                    {{ __('companies/view.roles.headers.edit') }}: {{ $role->human_resources_role_name }}
                @else
                    {{ __('companies/view.roles.headers.create') }}
                @endif
            </h5>
        </div>

        <div class="card-body p-4">

            <form action="{{ $isEdit ? route('companies.roles.update', [$company, $role]) : route('companies.roles.store', $company) }}" method="POST">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                {{-- Nome do Papel --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        {{ __('companies/view.roles.form.name') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="human_resources_role_name" class="form-control" required
                           value="{{ old('human_resources_role_name', $role->human_resources_role_name) }}">
                </div>

                {{-- Responsabilidades --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('companies/view.roles.form.responsability') }}</label>
                    <textarea name="human_resources_role_responsability" class="form-control" rows="4">{{ old('human_resources_role_responsability', $role->human_resources_role_responsability) }}</textarea>
                    <div class="form-text">{{ __('companies/view.roles.form.responsability_help') }}</div>
                </div>

                {{-- Autoridade --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('companies/view.roles.form.authority') }}</label>
                    <textarea name="human_resources_role_authority" class="form-control" rows="3">{{ old('human_resources_role_authority', $role->human_resources_role_authority) }}</textarea>
                    <div class="form-text">{{ __('companies/view.roles.form.authority_help') }}</div>
                </div>

                {{-- Competência --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('companies/view.roles.form.competence') }}</label>
                    <textarea name="human_resources_role_competence" class="form-control" rows="3">{{ old('human_resources_role_competence', $role->human_resources_role_competence) }}</textarea>
                    <div class="form-text">{{ __('companies/view.roles.form.competence_help') }}</div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('companies.show', $company) }}" class="btn btn-outline-secondary">
                        {{ __('companies/view.roles.actions.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> {{ __('companies/view.roles.actions.save') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
