@extends('dashboard')
@section('title', 'Projetos - dotProject+')

@section('dashboard-content')
    <div class="card shadow-sm">
        <div class="card-body p-4">

            {{-- CABEÇALHO E FILTROS --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3">
                <h1 class="h2 mb-0">Projetos</h1>

                {{-- Formulário de Filtros --}}
                <form class="d-flex flex-column flex-md-row justify-content-start align-items-center mb-3 gap-3"
                      role="search"
                      method="GET"
                      action="{{ route('projects.index') }}">

                    <input type="hidden" name="status" value="{{ $filterStatus }}">
                    <div>
                        <label for="owner" class="form-label form-label-sm mb-0 me-1">Responsável:</label>
                        <select id="owner" name="owner" class="form-select form-select-sm d-inline-block w-auto">
                            <option value="">Todos</option>
                            @foreach ($users as $ownerId => $ownerName)
                                <option value="{{ $ownerId }}" {{ $filterOwner === $ownerId ? 'selected' : '' }}>
                                    {{ $ownerName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="company" class="form-label form-label-sm mb-0 me-1">Empresa:</label>
                        <select id="company" name="company" class="form-select form-select-sm d-inline-block w-auto">
                            <option value="">Todas</option>
                            @foreach ($companies as $companyId => $companyName)
                                <option value="{{ $companyId }}" {{ $filterCompany === $companyId ? 'selected' : '' }}>
                                    {{ $companyName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                    <a href="{{ route('projects.create') }}" class="btn btn-dark btn-sm flex-shrink-0">Novo Projeto</a>
                </form>
            </div>

            <ul class="nav nav-tabs nav-tabs-dotproject mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ $filterStatus === 'all' ? 'active' : '' }}"
                       href="{{ route('projects.index', array_merge(request()->query(), ['status' => 'all'])) }}">
                        Todos
                    </a>
                </li>

                @foreach($statuses as $id => $name)
                    <li class="nav-item">
                        <a class="nav-link {{ (int) $filterStatus === (int) $id ? 'active' : '' }}"
                           href="{{ route('projects.index', array_merge(request()->query(), ['status' => $id])) }}">
                            {{ $name }}
                        </a>
                    </li>
                @endforeach

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Gantt
                    </a>
                </li>
            </ul>

            {{-- FORMULÁRIO DE ATUALIZAÇÃO EM MASSA (envolve a tabela) --}}
            <form method="POST" action="{{ route('projects.batchUpdate') }}">
                @csrf

                {{-- TABELA DE PROJETOS --}}
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dotproject">
                        <tr>
                            <th>Completa</th>
                            <th>Empresa</th>
                            <th>Nome do Projeto</th>
                            <th>Início</th>
                            <th>Encerramento</th>
                            <th>Atual.</th>
                            <th>Responsável</th>
                            <th>Atividades</th>
                            <th >Seleção</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($projects as $project)
                            <tr>
                                <td class="text-center align-middle">
                                    <div>
                                        {{ round($project->project_percent_complete ?? 0) }}%
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('companies.show', $project->company->company_id) }}">
                                        {{ $project->company->company_name ?? 'N/A' }}
                                    </a>
                                <td class="align-middle">
                                    <a href="{{ route('projects.show', $project) }}">
                                        {{ $project->project_name }}
                                    </a>
                                </td>
                                <td class="align-middle">{{ $project->project_start_date ? $project->project_start_date->format('d/m/Y') : '-' }}</td>
                                <td class="align-middle">{{ $project->project_end_date ? $project->project_end_date->format('d/m/Y') : '-' }}</td>
                                <td class="align-middle">-</td>
                                <td class="align-middle">{{ $project->owner->contact->full_name ?? 'N/A' }}</td>
                                <td class="align-middle">-</td>
                                <td class="text-center align-middle">
                                    <input type="checkbox" name="project_ids[]" value="{{ $project->project_id }}" class="form-check-input">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Nenhum projeto encontrado para este filtro.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINAÇÃO E AÇÕES EM MASSA --}}
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div>
                        {{ $projects->links('pagination::bootstrap-5') }}
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <select name="new_status" class="form-select form-select-sm" style="width: 200px;">
                            <option value="">Mudar status para...</option>
                            @foreach($statuses as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-secondary btn-sm flex-shrink-0">Atualizar status do projeto</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection
