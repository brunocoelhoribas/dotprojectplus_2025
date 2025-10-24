@php use App\Models\Company\Company; @endphp
@extends('dashboard')
@section('title', 'Empresas - dotProject+')

@section('dashboard-content')
    <div class="container">
        <h3>Adicionar Empresa</h3>

        <form action="{{ route('companies.store') }}" method="POST">

            @include('companies.form', ['company' => new Company])

            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('companies.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar Empresa</button>
                </div>
            </div>
        </form>
    </div>
@endsection
