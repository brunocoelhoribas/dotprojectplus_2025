@extends('dashboard')
@section('title', __('companies/view.index.title') . ' - dotProject+')

@section('dashboard-content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-end mb-4 gap-3">
                <div>
                    <h1 class="h4 fw-bold mb-0 text-dark">{{ __('companies/view.index.title') }}</h1>
                </div>

                <form class="d-flex flex-wrap align-items-end gap-2"
                      role="search"
                      method="GET"
                      action="{{ route('companies.index') }}">

                    <div>
                        <label for="search" class="form-label fw-bold small text-muted mb-0">{{ __('companies/view.index.search') }}</label>
                        <input class="form-control form-control-sm"
                               id="search"
                               type="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Buscar...">
                    </div>

                    <div>
                        <label for="companyResponsible" class="form-label fw-bold small text-muted mb-0">{{ __('companies/view.index.filter_owner') }}</label>
                        <select id="companyResponsible"
                                name="owner"
                                class="form-select form-select-sm"
                                style="min-width: 150px;">
                            <option value="all" {{ request('owner') === 'all' || !request('owner') ? 'selected' : '' }}>
                                {{ __('companies/view.index.all') }}
                            </option>
                            @foreach ($owners as $ownerId => $ownerName)
                                <option value="{{ $ownerId }}" {{ request('owner') === $ownerId ? 'selected' : '' }}>
                                    {{ $ownerName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">
                        {{ __('companies/view.index.btn_search') }}
                    </button>
                    <a href="{{ route('companies.create') }}" class="btn btn-dark btn-sm">
                        {{ __('companies/view.index.btn_new') }}
                    </a>
                </form>
            </div>

            <ul class="nav nav-tabs nav-tabs-dotproject mb-3" id="companyTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ !$selectedTypeId ? 'active' : '' }}"
                       href="{{ route('companies.index', request()->except('type')) }}"
                       role="tab"
                       aria-selected="{{ !$selectedTypeId ? 'true' : 'false' }}">
                        {{ __('companies/view.index.tab_all') }}
                    </a>
                </li>
                @foreach($types as $id => $name)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ (int)$selectedTypeId === (int)$id ? 'active' : '' }}"
                           href="{{ route('companies.index', array_merge(request()->query(), ['type' => $id])) }}"
                           role="tab"
                           aria-selected="{{ (int)$selectedTypeId === (int)$id ? 'true' : 'false' }}">
                            {{ $name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 35%;">{{ __('companies/view.index.table.name') }}</th>
                        <th style="width: 15%;" class="text-center">{{ __('companies/view.index.table.active_projects') }}</th>
                        <th style="width: 15%;" class="text-center">{{ __('companies/view.index.table.archived_projects') }}</th>
                        <th style="width: 25%;">{{ __('companies/view.index.table.type') }}</th>
                        <th style="width: 60px;">{{ __('companies/view.show.projects_tab.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td>
                                <a href="{{ route('companies.show', $company) }}"
                                   class="link-hover-yellow">
                                    {{ $company->company_name }}
                                </a>
                            </td>
                            <td class="text-center">{{ $company->active_projects_count }}</td>
                            <td class="text-center">{{ $company->archived_projects_count }}</td>
                            <td>{{ $types[$company->company_type] ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('companies.show', $company) }}"
                                   class="btn btn-xs btn-link text-dark p-0 me-2"
                                   title="{{ __('companies/view.index.actions.view') }}">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('companies.edit', $company) }}"
                                   class="btn btn-xs btn-link text-dark p-0"
                                   title="{{ __('companies/view.index.actions.edit') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3 small">
                                {{ __('companies/view.index.table.empty') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                {{ $companies->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection
