@php use App\Models\Company\Company; @endphp
@extends('dashboard')
@section('title', __('companies/view.create.page_title') . ' - dotProject+')

@section('dashboard-content')
    <div class="container">
        <h3>{{ __('companies/view.create.title') }}</h3>

        <form action="{{ route('companies.store') }}" method="POST">

            @include('companies.form', ['company' => new Company])

            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('companies.index') }}" class="btn btn-secondary me-2">{{ __('companies/view.form.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('companies/view.form.save') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
