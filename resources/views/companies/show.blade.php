@extends('dashboard')

@section('title', __('companies/view.show.page_title', ['name' => $company->company_name]))

@section('dashboard-content')
    @inject('carbon', 'Carbon\Carbon')
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">{{ $company->company_name }}</h1>

                <div class="btn-group" role="group">
                    <a href="{{ route('companies.edit', $company) }}" class="btn btn-secondary">{{ __('companies/view.show.edit_btn') }}</a>
                </div>
            </div>

            <div class="row mb-4">
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
                    {{-- Placeholder para contato principal --}}
                    <p class="mb-1"><strong>{{ __('companies/view.form.contact_section') }}:</strong> (Placeholder)</p>
                    <p class="mb-1"><strong>{{ __('companies/view.form.state') }}</strong> {{ $company->company_state ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>{{ __('companies/view.form.zip') }}</strong> {{ $company->company_zip ?? 'N/A' }}</p>
                </div>
            </div>

            <h5 class="h6 text-secondary text-uppercase">{{ __('companies/view.show.policy_title') }}</h5>
            <div class="bg-light p-3 rounded border mb-4">
                <dl class="row mb-0">
                    <dt class="col-sm-2">{{ __('companies/view.show.rewards') }}</dt>
                    <dd class="col-sm-10">{{ $company->policy->company_policies_recognition ?? 'N/A' }}</dd>

                    <dt class="col-sm-2">{{ __('companies/view.show.regulations') }}</dt>
                    <dd class="col-sm-10">{{ $company->policy->company_policies_policy ?? 'N/A' }}</dd>

                    <dt class="col-sm-2">{{ __('companies/view.show.safety') }}</dt>
                    <dd class="col-sm-10">{{ $company->policy->company_policies_safety ?? 'N/A' }}</dd>
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

            <div class="tab-content tab-content-dotproject" id="companyTabContent">

                <div class="tab-pane fade show active p-4" id="projetos" role="tabpanel" aria-labelledby="projetos-tab">
                    @if ($company->activeProjects->isEmpty())

                        <div class="text-center text-muted py-5">
                            <p class="mb-3">
                                {{ __('companies/view.show.projects_tab.empty') }}
                                {!! __('companies/view.show.projects_tab.click_here', ['link' => '<a href="'.route('projects.create', ['company_id' => $company->company_id]).'"><b><u>'.__('companies/view.show.projects_tab.here').'</u></b></a>']) !!}
                            </p>
                        </div>

                    @else

                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('projects.create', ['company_id' => $company->company_id]) }}" class="btn btn-primary">
                                {{ __('companies/view.show.projects_tab.add_btn') }}
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm align-middle">
                                <thead>
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
                                <tbody>
                                @foreach ($company->activeProjects as $project)
                                    <tr>
                                        <td class="text-end">{{ $project->project_percent_complete ?? 0 }}%</td>
                                        <td>
                                            <a href="{{ route('projects.show', $project->project_id) }}">{{ $project->project_name }}</a>
                                        </td>
                                        <td>{{ $carbon::parse($project->project_start_date)->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($project->project_end_date)
                                                {{ $carbon::parse($project->project_end_date)->format('d/m/Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $project->owner?->contact?->full_name ?? 'N/A' }}</td>
                                        <td>{{ $projectStatus[$project->project_status] ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('projects.edit', $project->project_id) }}" class="btn btn-sm btn-secondary">
                                                {{ __('companies/view.index.actions.edit') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade p-4" id="papeis" role="tabpanel" aria-labelledby="papeis-tab">
                    <p>{{ __('companies/view.show.placeholders.roles') }}</p>
                </div>
                <div class="tab-pane fade p-4" id="organograma" role="tabpanel" aria-labelledby="organograma-tab">
                    <p>{{ __('companies/view.show.placeholders.organogram') }}</p>
                </div>
                <div class="tab-pane fade p-4" id="rh" role="tabpanel" aria-labelledby="rh-tab">
                    <p>{{ __('companies/view.show.placeholders.hr') }}</p>
                </div>
            </div>

        </div>
    </div>
@endsection
