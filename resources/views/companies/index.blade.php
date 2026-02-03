@extends('dashboard')
@section('title', __('companies/view.index.title') . ' - dotProject+')

@section('dashboard-content')
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3">
                <h1 class="h2 mb-0">{{ __('companies/view.index.title') }}</h1>

                <form class="d-flex flex-column flex-md-row justify-content-start align-items-center mb-3 gap-3"
                      role="search"
                      method="GET"
                      action="{{ route('companies.index') }}">

                    <div>
                        <label for="search" class="form-label form-label-sm mb-0 me-1">{{ __('companies/view.index.search') }}</label>
                        <input class="form-control form-control-sm d-inline-block w-auto"
                               id="search"
                               type="search"
                               name="search"
                               value="{{ request('search') }}">
                    </div>


                    <div>
                        <label for="companyResponsible" class="form-label form-label-sm mb-0 me-1">{{ __('companies/view.index.filter_owner') }}</label>
                        <select id="companyResponsible"
                                name="owner"
                                class="form-select form-select-sm d-inline-block w-auto">


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

                    <button type="submit" class="btn btn-primary btn-sm">{{ __('companies/view.index.btn_search') }}</button>
                    <a href="{{ route('companies.create') }}" class="btn btn-dark btn-sm flex-shrink-0">{{ __('companies/view.index.btn_new') }}</a>
                </form>

            </div>

            <ul class="nav nav-tabs nav-tabs-dotproject mb-4">

                <li class="nav-item">
                    <a class="nav-link {{ !$selectedTypeId ? 'active' : '' }}"
                       href="{{ route('companies.index', request()->except('type')) }}">
                        {{ __('companies/view.index.tab_all') }}
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
                    <thead class="table-light">
                    <tr>
                        <th>{{ __('companies/view.index.table.name') }}</th>
                        <th>{{ __('companies/view.index.table.active_projects') }}</th>
                        <th>{{ __('companies/view.index.table.archived_projects') }}</th>
                        <th>{{ __('companies/view.index.table.type') }}</th>
                        <th style="width: 150px;">{{ __('companies/view.index.table.actions') }}</th>
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
                                       class="btn btn-sm btn-outline-secondary">{{ __('companies/view.index.actions.view') }}</a>
                                    <a href="{{ route('companies.edit', $company) }}"
                                       class="btn btn-sm btn-outline-primary">{{ __('companies/view.index.actions.edit') }}</a>
                                </td>
                            </tr>
                        @endforeach

                    @else
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                {{ __('companies/view.index.table.empty') }}
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
