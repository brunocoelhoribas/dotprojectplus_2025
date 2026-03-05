@extends('dashboard')
@section('title', __('projects/views.index.title') . ' - dotProject+')

@section('dashboard-content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-end mb-4 gap-3">
                <div>
                    <h1 class="h4 fw-bold mb-0 text-dark">{{ __('projects/views.index.title') }}</h1>
                </div>

                <form class="d-flex flex-wrap align-items-end gap-2"
                      role="search"
                      method="GET"
                      action="{{ route('projects.index') }}">

                    <input type="hidden" name="status" value="{{ $filterStatus }}">

                    <div>
                        <label for="owner" class="form-label fw-bold small text-muted mb-0">{{ __('projects/views.index.filters.owner') }}</label>
                        <select id="owner" name="owner" class="form-select form-select-sm" style="min-width: 150px;">
                            <option value="">{{ __('projects/views.index.filters.all_owners') }}</option>
                            @foreach ($users as $ownerId => $ownerName)
                                <option value="{{ $ownerId }}" {{ $filterOwner === $ownerId ? 'selected' : '' }}>
                                    {{ $ownerName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="company" class="form-label fw-bold small text-muted mb-0">{{ __('projects/views.index.filters.company') }}</label>
                        <select id="company" name="company" class="form-select form-select-sm" style="min-width: 150px;">
                            <option value="">{{ __('projects/views.index.filters.all_companies') }}</option>
                            @foreach ($companies as $companyId => $companyName)
                                <option value="{{ $companyId }}" {{ $filterCompany === $companyId ? 'selected' : '' }}>
                                    {{ $companyName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">
                        {{ __('projects/views.index.filters.filter_btn') }}
                    </button>
                    <a href="{{ route('projects.create') }}" class="btn btn-dark btn-sm">
                        {{ __('projects/views.new_project') }}
                    </a>
                </form>
            </div>

            <ul class="nav nav-tabs nav-tabs-dotproject mb-3">
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
                    <a class="nav-link" href="#">Gantt</a>
                </li>
            </ul>

            <form method="POST" action="{{ route('projects.batchUpdate') }}">
                @csrf

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="fw-bold text-dark py-2 text-center" style="width: 8%; background-color: #f8f9fa;">{{ __('projects/views.index.table.complete') }}</th>
                            <th class="fw-bold text-dark py-2" style="background-color: #f8f9fa;">{{ __('projects/views.index.table.company') }}</th>
                            <th class="fw-bold text-dark py-2" style="background-color: #f8f9fa;">{{ __('projects/views.index.table.name') }}</th>
                            <th class="fw-bold text-dark py-2" style="width: 12%; background-color: #f8f9fa;">{{ __('projects/views.index.table.start') }}</th>
                            <th class="fw-bold text-dark py-2" style="width: 12%; background-color: #f8f9fa;">{{ __('projects/views.index.table.end') }}</th>
                            <th class="fw-bold text-dark py-2" style="width: 10%; background-color: #f8f9fa;">{{ __('projects/views.index.table.updated') }}</th>
                            <th class="fw-bold text-dark py-2" style="background-color: #f8f9fa;">{{ __('projects/views.index.table.owner') }}</th>
                            <th class="fw-bold text-dark py-2 text-center" style="width: 5%; background-color: #f8f9fa;">{{ __('projects/views.index.table.tasks') }}</th>
                            <th class="fw-bold text-dark py-2 text-center" style="width: 5%; background-color: #f8f9fa;">{{ __('projects/views.index.table.selection') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white">
                        @forelse ($projects as $project)
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-secondary bg-opacity-25 text-dark border border-secondary border-opacity-25">
                                        {{ round($project->project_percent_complete ?? 0) }}%
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('companies.show', $project->company->company_id) }}" class="text-decoration-none text-dark small">
                                        {{ $project->company->company_name ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('projects.show', $project) }}" class="link-hover-yellow">
                                        {{ $project->project_name }}
                                    </a>
                                </td>
                                <td class="small">{{ $project->project_start_date ? $project->project_start_date->format('d/m/Y') : '-' }}</td>
                                <td class="small">{{ $project->project_end_date ? $project->project_end_date->format('d/m/Y') : '-' }}</td>
                                <td class="small text-muted">-</td>
                                <td class="small">{{ $project->owner->contact->full_name ?? 'N/A' }}</td>
                                <td class="text-center small text-muted">-</td>
                                <td class="text-center">
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

                <div class="mt-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                    <div>
                        {{ $projects->links('pagination::bootstrap-5') }}
                    </div>

                    @if($projects->count() > 0)
                        <div class="d-flex align-items-center gap-2 bg-light p-2 rounded border">
                            <label class="small fw-bold text-muted mb-0">{{ __('projects/views.index.batch.change_status') }}:</label>
                            <select name="new_status" class="form-select form-select-sm" style="width: 180px;">
                                @foreach($statuses as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-secondary btn-sm">
                                {{ __('projects/views.index.batch.update_btn') }}
                            </button>
                        </div>
                    @endif
                </div>

            </form>

        </div>
    </div>
@endsection
