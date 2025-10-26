@extends('dashboard')
@section('title', 'Empresas - dotProject+')

@section('dashboard-content')
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3">
                <h1 class="h2 mb-0">Empresas</h1>

                <form class="d-flex flex-column flex-md-row justify-content-start align-items-center mb-3 gap-3"
                      role="search"
                      method="GET"
                      action="{{ route('companies.index') }}">

                    <div>
                        <label for="search" class="form-label form-label-sm mb-0 me-1">Busca:</label>
                        <input class="form-control form-control-sm d-inline-block w-auto"
                               id="search"
                               type="search"
                               name="search"
                               value="{{ request('search') }}">
                    </div>


                    <div>
                        <label for="companyResponsible" class="form-label form-label-sm mb-0 me-1">Filtro por responsável:</label>
                        <select id="companyResponsible"
                                name="owner"
                                class="form-select form-select-sm d-inline-block w-auto">


                            <option value="all" {{ request('owner') === 'all' || !request('owner') ? 'selected' : '' }}>
                                Todos
                            </option>

                            @foreach ($owners as $ownerId => $ownerName)
                                <option value="{{ $ownerId }}" {{ request('owner') === $ownerId ? 'selected' : '' }}>
                                    {{ $ownerName }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
                    <a href="{{ route('companies.create') }}" class="btn btn-dark btn-sm flex-shrink-0">Nova Empresa</a>
                </form>

            </div>

            <ul class="nav nav-tabs nav-tabs-dotproject mb-4">

                <li class="nav-item">
                    <a class="nav-link {{ !$selectedTypeId ? 'active' : '' }}"
                       href="{{ route('companies.index', request()->except('type')) }}">
                        Todas Empresas
                    </a>
                </li>

                @foreach($types as $id => $name)
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedTypeId === $id ? 'active' : '' }}"
                           href="{{ route('companies.index', array_merge(request()->query(), ['type' => $id])) }}">
                            {{ $name }}
                        </a>
                    </li>
                @endforeach
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
                                <td>{{ $types[$company->company_type] ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('companies.show', $company) }}"
                                       class="btn btn-sm btn-outline-secondary">Ver</a>
                                    <a href="{{ route('companies.edit', $company) }}"
                                       class="btn btn-sm btn-outline-primary">Editar</a>
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
