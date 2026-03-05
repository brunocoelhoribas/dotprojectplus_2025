@php use App\Models\Company\Company; @endphp
@extends('dashboard')
@section('title', __('companies/view.create.page_title') . ' - dotProject+')

@section('dashboard-content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">
                <i class="bi bi-building-add me-2 text-warning"></i>{{ __('companies/view.create.title') }}
            </h4>
            <small class="text-muted">{{ __('companies/view.create.page_title') }}</small>
        </div>
        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>{{ __('companies/view.form.cancel') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>{{ __('companies/view.form.validation_error') ?? 'Verifique os campos abaixo:' }}</strong>
            <ul class="mb-0 mt-2 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('companies.store') }}" method="POST">

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light d-flex align-items-center p-3">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-info-circle me-2 text-warning"></i>{{ __('companies/view.form.main_info') }}
                </h5>
            </div>
            <div class="card-body p-4">
                @include('companies.form', ['company' => new Company])
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary btn-sm px-4">
                <i class="bi bi-x-lg me-1"></i>{{ __('companies/view.form.cancel') }}
            </a>
            <button type="submit" class="btn btn-primary btn-sm px-4">
                <i class="bi bi-check-lg me-1"></i>{{ __('companies/view.form.save') }}
            </button>
        </div>

    </form>

@endsection