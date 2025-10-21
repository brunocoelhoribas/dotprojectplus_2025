@extends('dashboard')

@section('title', 'Ver Empresa: ' . $company->company_name)

@push('styles')
    <style>
        :root {
            --dp-tab-on: #E6B800;
            --dp-tab-head: #FFE680;
            --dp-tab-inactive: #f8f9fa;
        }

        .nav-tabs-dotproject .nav-link {
            border: 1px solid #dee2e6;
            border-bottom: none;
            background-color: var(--dp-tab-inactive);
            color: #495057;
        }

        .nav-tabs-dotproject .nav-link.active {
            background-color: var(--dp-tab-on);
            border-color: var(--dp-tab-on);
            color: #333;
            font-weight: bold;
        }

        /* Para o conteúdo das abas */
        .tab-content-dotproject {
            background-color: var(--dp-tab-inactive);
            border: 1px solid #dee2e6;
            border-top: none;
        }
    </style>
@endpush


@section('dashboard-content')
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">{{ $company->company_name }}</h1>

                <div class="btn-group" role="group">
                    <a href="{{ route('companies.edit', $company) }}" class="btn btn-secondary">Editar</a>
                    <a href="#" class="btn btn-secondary">Usuários</a>
                    <a href="#" class="btn btn-secondary">Contatos</a>
                </div>
            </div>

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
                    <dd class="col-sm-10">{{ $policies->company_policies_recognition ?? 'N/A' }}</dd>

                    <dt class="col-sm-2">Regulamentação:</dt>
                    <dd class="col-sm-10">{{ $policies->company_policies_policy ?? 'N/A' }}</dd>

                    <dt class="col-sm-2">Segurança:</dt>
                    <dd class="col-sm-10">{{ $policies->company_policies_safety ?? 'N/A' }}</dd>
                </dl>
            </div>

            <ul class="nav nav-tabs nav-tabs-dotproject" id="companyTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="projetos-tab" data-bs-toggle="tab" data-bs-target="#projetos" type="button" role="tab" aria-controls="projetos" aria-selected="true">Projetos</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="papeis-tab" data-bs-toggle="tab" data-bs-target="#papeis" type="button" role="tab" aria-controls="papeis" aria-selected="false">Papéis da organização</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="organograma-tab" data-bs-toggle="tab" data-bs-target="#organograma" type="button" role="tab" aria-controls="organograma" aria-selected="false">Organograma</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rh-tab" data-bs-toggle="tab" data-bs-target="#rh" type="button" role="tab" aria-controls="rh" aria-selected="false">Recursos Humanos</button>
                </li>
            </ul>

            <div class="tab-content tab-content-dotproject" id="companyTabContent">

                <div class="tab-pane fade show active p-4" id="projetos" role="tabpanel" aria-labelledby="projetos-tab">
                    {{-- TODO: Adicionar tabela de Projetos Ativos e Arquivados aqui (vw_active.php, vw_archived.php) --}}
                    <p class="text-center text-muted">
                        Ainda não existe nenhum projeto cadastrado.
                        <a href="#">Clique aqui</a> para criar um projeto.
                    </p>
                </div>

                <div class="tab-pane fade p-4" id="papeis" role="tabpanel" aria-labelledby="papeis-tab">
                    <p>Conteúdo da aba "Papéis da organização" (vw_roles).</p>
                </div>
                <div class="tab-pane fade p-4" id="organograma" role="tabpanel" aria-labelledby="organograma-tab">
                    <p>Conteúdo da aba "Organograma".</p>
                </div>
                <div class="tab-pane fade p-4" id="rh" role="tabpanel" aria-labelledby="rh-tab">
                    <p>Conteúdo da aba "Recursos Humanos" (vw_users).</p>
                </div>
            </div>

        </div>
    </div
@endsection
