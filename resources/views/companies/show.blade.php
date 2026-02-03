@extends('dashboard')

@section('title', __('companies/view.show.page_title', ['name' => $company->company_name]))

@section('dashboard-content')
    @inject('carbon', 'Carbon\Carbon')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h4 fw-bold mb-0 text-dark">{{ $company->company_name }}</h1>

                <div class="btn-group" role="group">
                    <a href="{{ route('companies.edit', $company) }}" class="btn btn-outline-primary btn-sm">
                        {{ __('companies/view.show.edit_btn') }}
                    </a>
                </div>
            </div>

            <div class="row mb-4 small">
                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>{{ __('companies/view.form.name') }}</strong>
                        <span class="text-danger">*</span> {{ $company->company_name }}
                    </p>
                    <p class="mb-1"><strong>{{ __('companies/view.form.phone') }}</strong> {{ $company->company_phone1 ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>{{ __('companies/view.form.email') }}</strong> {{ $company->company_email ?? 'N/A' }}</p>
                    <p class="mb-1">
                        <strong>{{ __('companies/view.form.address') }}</strong>
                        {{ $company->company_address1 }} {{ $company->company_address2 }}
                    </p>
                    <p class="mb-1"><strong>{{ __('companies/view.form.city') }}</strong> {{ $company->company_city ?? 'N/A' }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>{{ __('companies/view.form.owner') }}</strong>
                        {{ $company->owner?->contact?->full_name ?? 'N/A' }}
                    </p>
                    <p class="mb-1"><strong>{{ __('companies/view.form.contact_section') }}:</strong> --- </p>
                    <p class="mb-1"><strong>{{ __('companies/view.form.state') }}</strong> {{ $company->company_state ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>{{ __('companies/view.form.zip') }}</strong> {{ $company->company_zip ?? 'N/A' }}</p>
                </div>
            </div>

            <h5 class="h6 text-secondary text-uppercase fw-bold mb-2">{{ __('companies/view.show.policy_title') }}</h5>
            <div class="bg-light p-3 rounded border mb-4 small">
                <dl class="row mb-0">
                    <dt class="col-sm-2 text-muted">{{ __('companies/view.show.rewards') }}</dt>
                    <dd class="col-sm-10 mb-1">{{ $company->policy->company_policies_recognition ?? 'N/A' }}</dd>

                    <dt class="col-sm-2 text-muted">{{ __('companies/view.show.regulations') }}</dt>
                    <dd class="col-sm-10 mb-1">{{ $company->policy->company_policies_policy ?? 'N/A' }}</dd>

                    <dt class="col-sm-2 text-muted">{{ __('companies/view.show.safety') }}</dt>
                    <dd class="col-sm-10 mb-0">{{ $company->policy->company_policies_safety ?? 'N/A' }}</dd>
                </dl>
            </div>


            <ul class="nav nav-tabs nav-tabs-dotproject" id="companyTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="projetos-tab" data-bs-toggle="tab" data-bs-target="#projetos"
                            type="button" role="tab" aria-controls="projetos" aria-selected="true">{{ __('companies/view.show.tabs.projects') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="papeis-tab" data-bs-toggle="tab" data-bs-target="#papeis" type="button"
                            role="tab" aria-controls="papeis" aria-selected="false">{{ __('companies/view.show.tabs.roles') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="organograma-tab" data-bs-toggle="tab" data-bs-target="#organograma"
                            type="button" role="tab" aria-controls="organograma" aria-selected="false">{{ __('companies/view.show.tabs.organogram') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rh-tab" data-bs-toggle="tab" data-bs-target="#rh" type="button"
                            role="tab" aria-controls="rh" aria-selected="false">{{ __('companies/view.show.tabs.hr') }}
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="companyTabContent">
                <div class="tab-pane fade show active" id="projetos" role="tabpanel" aria-labelledby="projetos-tab">

                    <div class="d-flex justify-content-end mb-3 mt-3">
                        <a href="{{ route('projects.create', ['company_id' => $company->company_id]) }}"
                           class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> {{ __('companies/view.show.projects_tab.add_btn') }}
                        </a>
                    </div>

                    @if ($company->activeProjects->isEmpty())
                        <div class="text-center text-muted py-5 border rounded bg-light">
                            <i class="bi bi-folder-x display-6 mb-3 d-block opacity-50"></i>
                            <p class="mb-0">
                                {{ __('companies/view.show.projects_tab.empty') }}
                            </p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 8%;" class="text-end">{{ __('companies/view.show.projects_tab.table.complete') }}</th>
                                    <th>{{ __('companies/view.show.projects_tab.table.project') }}</th>
                                    <th style="width: 10%;">{{ __('companies/view.show.projects_tab.table.start') }}</th>
                                    <th style="width: 10%;">{{ __('companies/view.show.projects_tab.table.end') }}</th>
                                    <th>{{ __('companies/view.show.projects_tab.table.owner') }}</th>
                                    <th>{{ __('companies/view.show.projects_tab.table.status') }}</th>
                                    <th style="width: 10%;" class="text-center">{{ __('companies/view.show.projects_tab.table.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                @foreach ($company->activeProjects as $project)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $project->project_percent_complete ?? 0 }}%</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('projects.show', $project->project_id) }}" class="text-dark text-decoration-none link-hover-yellow">
                                                {{ $project->project_name }}
                                            </a>
                                        </td>
                                        <td class="small">{{ $carbon::parse($project->project_start_date)->format('d/m/Y') }}</td>
                                        <td class="small">
                                            @if ($project->project_end_date)
                                                {{ $carbon::parse($project->project_end_date)->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="small">{{ $project->owner?->contact?->full_name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            @php
                                                $statusClass = match($project->project_status) {
                                                    3 => 'bg-success',
                                                    5 => 'bg-success',
                                                    1 => 'bg-warning text-dark',
                                                    2 => 'bg-info text-dark',
                                                    4 => 'bg-warning text-dark',
                                                    7 => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} bg-opacity-75" style="font-weight: normal;">
                                                {{ $projectStatus[$project->project_status] ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('projects.show', $project->project_id) }}"
                                                   class="btn btn-xs btn-link text-dark p-0"
                                                   title="{{ __('companies/view.index.actions.view') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <a href="{{ route('projects.edit', $project->project_id) }}"
                                                   class="btn btn-xs btn-link text-dark p-0 me-2"
                                                   title="{{ __('companies/view.index.actions.edit') }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="tab-pane fade p-4" id="papeis" role="tabpanel" aria-labelledby="papeis-tab">
                    <p class="text-muted">{{ __('companies/view.show.placeholders.roles') }}</p>
                </div>
                <div class="tab-pane fade p-4" id="organograma" role="tabpanel" aria-labelledby="organograma-tab">
                    <p class="text-muted">{{ __('companies/view.show.placeholders.organogram') }}</p>
                </div>
                <div class="tab-pane fade p-4" id="rh" role="tabpanel" aria-labelledby="rh-tab">
                    <p class="text-muted">{{ __('companies/view.show.placeholders.hr') }}</p>
                </div>
            </div>

        </div>
    </div>
@endsection
