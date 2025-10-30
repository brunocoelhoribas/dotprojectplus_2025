@php use Carbon\Carbon; @endphp
@extends('dashboard')

@section('title', 'Ver Empresa: ' . $company->company_name)

@section('dashboard-content')
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">{{ $company->company_name }}</h1>

                <div class="btn-group" role="group">
                    <a href="{{ route('companies.edit', $company) }}" class="btn btn-secondary">Editar</a>
                </div>
            </div>

            {{-- ... (O restante dos detalhes da empresa e política organizacional permanece o mesmo) ... --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Nome:</strong>
                        <span class="text-danger">*</span> {{ $company->company_name }}
                    </p>
                    <p class="mb-1"><strong>Telefone:</strong> {{ $company->company_phone1 ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>E-mail:</strong> {{ $company->company_email ?? 'N/A' }}</p>
                    <p class="mb-1">
                        <strong>Endereço:</strong>
                        {{ $company->company_address1 }} {{ $company->company_address2 }}
                    </p>
                    <p class="mb-1"><strong>Cidade:</strong> {{ $company->company_city ?? 'N/A' }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Responsável:</strong>
                        {{ $company->owner?->contact?->full_name ?? 'N/A' }}
                    </p>
                    <p class="mb-1"><strong>Contato:</strong> (Placeholder)</p>
                    <p class="mb-1"><strong>Estado:</strong> {{ $company->company_state ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>CEP:</strong> {{ $company->company_zip ?? 'N/A' }}</p>
                </div>
            </div>

            <h5 class="h6 text-secondary text-uppercase">Política Organizacional</h5>
            <div class="bg-light p-3 rounded border mb-4">
                <dl class="row mb-0">
                    <dt class="col-sm-2">Recompensas:</dt>
                    <dd class="col-sm-10">{{ $company->policy->company_policies_recognition ?? 'N/A' }}</dd>

                    <dt class="col-sm-2">Regulamentação:</dt>
                    <dd class="col-sm-10">{{ $company->policy->company_policies_policy ?? 'N/A' }}</dd>

                    <dt class="col-sm-2">Segurança:</dt>
                    <dd class="col-sm-10">{{ $company->policy->company_policies_safety ?? 'N/A' }}</dd>
                </dl>
            </div>


            <ul class="nav nav-tabs nav-tabs-dotproject" id="companyTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="projetos-tab" data-bs-toggle="tab" data-bs-target="#projetos"
                            type="button" role="tab" aria-controls="projetos" aria-selected="true">Projetos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="papeis-tab" data-bs-toggle="tab" data-bs-target="#papeis" type="button"
                            role="tab" aria-controls="papeis" aria-selected="false">Papéis da organização
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="organograma-tab" data-bs-toggle="tab" data-bs-target="#organograma"
                            type="button" role="tab" aria-controls="organograma" aria-selected="false">Organograma
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rh-tab" data-bs-toggle="tab" data-bs-target="#rh" type="button"
                            role="tab" aria-controls="rh" aria-selected="false">Recursos Humanos
                    </button>
                </li>
            </ul>

            <div class="tab-content tab-content-dotproject" id="companyTabContent">

                <div class="tab-pane fade show active p-4" id="projetos" role="tabpanel" aria-labelledby="projetos-tab">

                    @if ($company->activeProjects->isEmpty())

                        <div class="text-center text-muted py-5">
                            <p class="mb-3">Ainda não existe nenhum projeto cadastrado.
                                {{-- Ajuste este link para sua rota de criação de projetos --}}
                                Clique <a href="{{-- route('projects.create', ['company_id' => $company->company_id]) --}}">aqui</a> para criar um projeto.
                            </p>
                        </div>

                    @else

                        <div class="d-flex justify-content-end mb-3">
                            {{-- Ajuste este link para sua rota de criação de projetos --}}
                            <a href="{{-- route('projects.create', ['company_id' => $company->company_id]) --}}" class="btn btn-primary">
                                Adicionar Projeto
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm align-middle">
                                <thead>
                                <tr>
                                    <th style="width: 8%;" class="text-end">Completa</th>
                                    <th>Projeto</th>
                                    <th style="width: 10%;">Início</th>
                                    <th style="width: 10%;">Fim</th>
                                    <th>Responsável</th>
                                    <th>Status</th>
                                    <th style="width: 10%;" class="text-center">Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($company->activeProjects as $project)
                                    <tr>
                                        <td class="text-end">{{ $project->project_percent_complete ?? 0 }}%</td>

                                        <td>
                                            {{-- Ajuste este link para sua rota de visualização de projeto --}}
                                            <a href="#">{{ $project->project_name }}</a>
                                        </td>

                                        <td>{{ Carbon::parse($project->project_start_date)->format('d/m/Y') }}</td>

                                        <td>
                                            @if ($project->project_end_date)
                                                {{ Carbon::parse($project->project_end_date)->format('d/m/Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td>{{ $project->owner?->contact?->full_name ?? 'N/A' }}</td>

                                        <td>{{ $projectStatus[$project->project_status] ?? 'N/A' }}</td>

                                        <td class="text-center">
                                            {{-- Ajuste este link para sua rota de edição de projeto --}}
                                            <a href="{{-- route('projects.edit', $project->project_id) --}}" class="btn btn-sm btn-secondary">
                                                Editar
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
                    <p>Conteúdo da aba "Papéis da organização".</p>
                    {{-- TODO: Implementar vw_roles.php --}}
                </div>
                <div class="tab-pane fade p-4" id="organograma" role="tabpanel" aria-labelledby="organograma-tab">
                    <p>Conteúdo da aba "Organograma".</p>
                    {{-- TODO: Implementar vw_depts.php --}}
                </div>
                <div class="tab-pane fade p-4" id="rh" role="tabpanel" aria-labelledby="rh-tab">
                    <p>Conteúdo da aba "Recursos Humanos".</p>
                    {{-- TODO: Implementar vw_users.php e vw_contacts.php --}}
                </div>
            </div>

        </div>
    </div>
@endsection
