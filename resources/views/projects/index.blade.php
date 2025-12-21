@extends('dashboard')
@section('title', __('projects/views.index.title') . ' - dotProject+')

@section('dashboard-content')
    <div class="card shadow-sm">
        <div class="card-body p-4">

            {{-- CABEÇALHO E FILTROS --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3">
                <h1 class="h2 mb-0">{{ __('projects/views.index.title') }}</h1>

                {{-- Formulário de Filtros --}}
                <form class="d-flex flex-column flex-md-row justify-content-start align-items-center mb-3 gap-3"
                      role="search"
                      method="GET"
                      action="{{ route('projects.index') }}">

                    <input type="hidden" name="status" value="{{ $filterStatus }}">
                    <div>
                        <label for="owner" class="form-label form-label-sm mb-0 me-1">{{ __('projects/views.index.filters.owner') }}</label>
                        <select id="owner" name="owner" class="form-select form-select-sm d-inline-block w-auto">
                            <option value="">{{ __('projects/views.index.filters.all_owners') }}</option>
                            @foreach ($users as $ownerId => $ownerName)
                                <option value="{{ $ownerId }}" {{ $filterOwner === $ownerId ? 'selected' : '' }}>
                                    {{ $ownerName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="company" class="form-label form-label-sm mb-0 me-1">{{ __('projects/views.index.filters.company') }}</label>
                        <select id="company" name="company" class="form-select form-select-sm d-inline-block w-auto">
                            <option value="">{{ __('projects/views.index.filters.all_companies') }}</option>
                            @foreach ($companies as $companyId => $companyName)
                                <option value="{{ $companyId }}" {{ $filterCompany === $companyId ? 'selected' : '' }}>
                                    {{ $companyName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">{{ __('projects/views.index.filters.filter_btn') }}</button>
                    <a href="{{ route('projects.create') }}" class="btn btn-dark btn-sm flex-shrink-0">{{ __('projects/views.new_project') }}</a>
                </form>
            </div>

            <ul class="nav nav-tabs nav-tabs-dotproject mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ $filterStatus === 'all' ? 'active' : '' }}"
                       href="{{ route('projects.index', array_merge(request()->query(), ['status' => 'all'])) }}">
                        {{ __('projects/views.index.filters.status_all') }}
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

            <form method="POST" action="{{ route('projects.batchUpdate') }}">
                @csrf

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dotproject">
                        <tr>
                            <th>{{ __('projects/views.index.table.complete') }}</th>
                            <th>{{ __('projects/views.index.table.company') }}</th>
                            <th>{{ __('projects/views.index.table.name') }}</th>
                            <th>{{ __('projects/views.index.table.start') }}</th>
                            <th>{{ __('projects/views.index.table.end') }}</th>
                            <th>{{ __('projects/views.index.table.updated') }}</th>
                            <th>{{ __('projects/views.index.table.owner') }}</th>
                            <th>{{ __('projects/views.index.table.tasks') }}</th>
                            <th >{{ __('projects/views.index.table.selection') }}</th>
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
                                    {{ __('projects/views.index.table.empty') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div>
                        {{ $projects->links('pagination::bootstrap-5') }}
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <select name="new_status" class="form-select form-select-sm" style="width: 200px;">
                            <option value="">{{ __('projects/views.index.batch.change_status') }}</option>
                            @foreach($statuses as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-secondary btn-sm flex-shrink-0">{{ __('projects/views.index.batch.update_btn') }}</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection
