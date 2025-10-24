@extends('dashboard')

@section('title', 'Editar Empresa: ' . $company->company_name)

@section('dashboard-content')
    <h1 class="h2 mb-4">Editar Empresa</h1>

    <form action="{{ route('companies.update', $company) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                @include('companies.form')
            </div>
        </div>

        <hr class="my-4">
        <h4>Política Organizacional</h4>
        <div class="row">
            <div class="col-12">

                <div class="mb-3">
                    <label for="company_policies_recognition" class="form-label">
                        Recompensas e reconhecimentos:
                    </label>
                    <textarea id="company_policies_recognition" name="company_policies_recognition" class="form-control" rows="5"
                    >{{ old('company_policies_recognition', $company->policy->company_policies_recognition ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="company_policies_policy" class="form-label">
                        Regulamentos, padrões, e comprimento de políticas:
                    </label>
                    <textarea id="company_policies_policy" name="company_policies_policy" class="form-control" rows="5"
                    >{{ old('company_policies_policy', $company->policy->company_policies_policy ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="company_policies_safety" class="form-label">
                        Segurança:
                    </label>
                    <textarea id="company_policies_safety" name="company_policies_safety" class="form-control" rows="5"
                    >{{ old('company_policies_safety', $company->policy->company_policies_safety ?? '') }}</textarea>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('companies.show', $company) }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-dark">Submeter</button>
        </div>

    </form>
@endsection
