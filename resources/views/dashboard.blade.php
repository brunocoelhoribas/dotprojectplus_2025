@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column min-vh-100">

        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
                <div class="container">
                    <a class="navbar-brand fw-bold text-warning" href="#">dotProject+</a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="main-nav">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link" href="{{ route('companies.index') }}">Empresas</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('projects.index') }}">Projetos</a></li>
                        </ul>

                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    Admin, Sempre
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">Meus Dados</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Sair</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <main class="flex-grow-1 container py-4 py-md-5">
            @yield('dashboard-content')
        </main>

        <footer class="text-center py-4 text-muted small">
            <p class="mb-1">dotProject+ | Uma Ferramenta Educacional para o Gerenciamento de Projetos</p>
            <a href="http://www.gqs.ufsc.br/evolution-of-dotproject/" target="_blank">www.gqs.ufsc.br/evolution-of-dotproject</a>
        </footer>

    </div>
@endsection
