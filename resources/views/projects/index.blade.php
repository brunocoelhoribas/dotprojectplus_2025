@extends('dashboard')

@section('title', 'Projetos - dotProject+')

@push('styles')
    <style>
        .thead-custom {
            background-color: #FFD700;
        }
    </style>
@endpush


@section('dashboard-content')

    <h1 class="h2 mb-4">Projetos</h1>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <label for="responsavel" class="form-label small mb-0">Responsável:</label>
                        <select id="responsavel" class="form-select form-select-sm">
                            <option>Todos</option>
                        </select>
                    </div>
                    <div>
                        <label for="empresa" class="form-label small mb-0">Empresa/Divisão:</label>
                        <select id="empresa" class="form-select form-select-sm">
                            <option>Todas</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-dark w-100 w-md-auto">
                    Novo projeto
                </button>
            </div>

            <ul class="nav nav-tabs mb-4">
                <li class="nav-item"><a class="nav-link active" href="#">Todos (n)</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Em violação</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Em Planejamento (n)</a></li>
                {{-- ... outras abas ... --}}
            </ul>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-custom">
                    <tr>
                        <th>Cor (n)</th>
                        <th>Empresa</th>
                        <th>Nome do Projeto</th>
                        <th>Início</th>
                        <th>Encerramento</th>
                        <th>Atual.</th>
                        <th>P</th>
                        <th>Responsável</th>
                        <th>Atividades (Venc)</th>
                        <th>Seleção</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            Não ha projetos disponíveis
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
