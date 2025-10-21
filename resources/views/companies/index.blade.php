@extends('dashboard')
@section('title', 'Empresas - dotProject+')

@push('styles')
    <style>
        :root {
            --dp-tab-on: #E6B800;
            --dp-tab-head: #FFE680;
        }
        .nav-tabs-dotproject .nav-link {
            border: 1px solid #dee2e6;
            border-bottom: none;
            background-color: #f8f9fa;
            color: #495057;
        }
        .nav-tabs-dotproject .nav-link.active {
            background-color: var(--dp-tab-on);
            border-color: var(--dp-tab-on);
            color: #333;
            font-weight: bold;
        }
        .thead-dotproject {
            background-color: var(--dp-tab-head);
            color: #333;
            border-color: #dee2e6;
        }
    </style>
@endpush

@section('dashboard-content')
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3">
                <h1 class="h2 mb-0">Empresas</h1>

                <form class="d-flex flex-grow-1 flex-md-grow-0 justify-content-end align-items-center gap-2" role="search">
                    <div>
                        <label for="search" class="form-label form-label-sm mb-0 me-1">Busca:</label>
                        <input class="form-control form-control-sm d-inline-block w-auto" id="search" type="search">
                    </div>
                    <div>
                        <label for="responsavel" class="form-label form-label-sm mb-0 me-1">Filtro por responsável:</label>
                        <select id="responsavel" class="form-select form-select-sm d-inline-block w-auto">
                            <option value="todos">Todos</option>
                        </select>
                    </div>
                    <a href="{{ route('companies.create') }}" class="btn btn-dark btn-sm flex-shrink-0">Nova Empresa</a>
                </form>
            </div>

            <ul class="nav nav-tabs nav-tabs-dotproject mb-4">
                <li class="nav-item"><a class="nav-link active" href="#">Todas Empresas</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Cliente</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Fabricante</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Fornecedor</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Consultor</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Governo</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Interno</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Não aplicável</a></li>
            </ul>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dotproject">
                    <tr>
                        <th>Nome da Empresa</th>
                        <th>Projetos Ativos</th>
                        <th>Projetos Arquivados</th>
                        <th>Tipo</th>
                        <th style="width: 150px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($companies))
                        @foreach ($companies as $company)
                            <tr>
                                <td>{{ $company->company_name }}</td>
                                <td>{{ $company->active_projects_count }}</td>
                                <td>{{ $company->archived_projects_count }}</td>
                                <td>{{ $company->company_type_name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('companies.show', $company) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                                    <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                </td>
                            </tr>
                        @endforeach

                    @else
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Nenhuma empresa disponível
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $companies->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection
