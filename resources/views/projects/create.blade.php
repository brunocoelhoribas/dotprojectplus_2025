@extends('dashboard')

@section('title', 'Criar projeto')

@section('dashboard-content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="mb-0">Criar Novo Projeto</h3>
                    </div>
                    <div class="card-body">

                        {{-- Exibe erros de validação --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('projects.store') }}">
                            @csrf

                            <div class="row">
                                {{-- Coluna da Esquerda --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="project_name" class="form-label">Nome do Projeto *</label>
                                        <input type="text" class="form-control" id="project_name" name="project_name"
                                               value="{{ old('project_name') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_company" class="form-label">Empresa *</label>
                                        <select class="form-select" id="project_company" name="project_company" required>
                                            <option value="">Selecione uma Companhia</option>
                                            @foreach($companies as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_company', $companyId) === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_owner" class="form-label">Dono do Projeto *</label>
                                        <select class="form-select" id="project_owner" name="project_owner" required>
                                            <option value="">Selecione um Dono</option>
                                            @foreach($users as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_owner', auth()->id()) === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_target_budget" class="form-label">Orçamento Previsto</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" step="0.01" class="form-control" id="project_target_budget"
                                                   name="project_target_budget" value="{{ old('project_target_budget') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Coluna da Direita --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="project_start_date" class="form-label">Data de Início</label>
                                        <input type="date" class="form-control" id="project_start_date"
                                               name="project_start_date" value="{{ old('project_start_date') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_end_date" class="form-label">Data Final Alvo</label>
                                        <input type="date" class="form-control" id="project_end_date"
                                               name="project_end_date" value="{{ old('project_end_date') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_status" class="form-label">Status *</label>
                                        <select class="form-select" id="project_status" name="project_status" required>
                                            @foreach($statuses as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_status') === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="project_priority" class="form-label">Prioridade *</label>
                                        <select class="form-select" id="project_priority" name="project_priority" required>
                                            @foreach($priorities as $id => $name)
                                                <option value="{{ $id }}" {{ old('project_priority') === $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Campos Adicionais (linha inteira) --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="project_description" class="form-label">Descrição</label>
                                        <textarea class="form-control" id="project_description" name="project_description"
                                                  rows="4">{{ old('project_description') }}</textarea>
                                    </div>
                                </div>

                                {{--
                                  Campos M2M (Departamentos e Contatos).
                                  O 'addedit.php' usa pop-ups JS para preencher estes campos.
                                  Para uma migração moderna, você usaria um seletor (como Select2)
                                  para preenchê-los. Por enquanto, são campos hidden que o seu
                                  Controller (store) espera.
                                --}}
                                <input type="hidden" name="project_departments" id="project_departments" value="{{ old('project_departments') }}">
                                <input type="hidden" name="project_contacts" id="project_contacts" value="{{ old('project_contacts') }}">

                            </div>

                            <hr>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('projects.index') }}" class="btn btn-secondary me-3">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Salvar Projeto</button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
