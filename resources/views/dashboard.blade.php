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
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('companies.index') }}">{{ __('layout.nav.companies') }}</a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('projects.index') }}">{{ __('layout.nav.projects') }}</a>
                            </li>
                        </ul>

                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">

                            <li class="nav-item dropdown me-3">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-globe me-1"></i>
                                    {{ strtoupper(app()->getLocale()) === 'PT_BR' ? 'PT' : strtoupper(app()->getLocale()) }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li>
                                        <a class="dropdown-item d-flex justify-content-between align-items-center"
                                            href="{{ route('lang.switch', 'pt_BR') }}">
                                            Português <span>🇧🇷</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex justify-content-between align-items-center"
                                            href="{{ route('lang.switch', 'en') }}">
                                            English <span>🇺🇸</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex justify-content-between align-items-center"
                                            href="{{ route('lang.switch', 'es') }}">
                                            Español <span>🇪🇸</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown border-start ps-3 border-secondary">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-bs-toggle="dropdown">
                                    Admin, Sempre
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">{{ __('layout.nav.my_data') }}</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="dropdown-item">{{ __('layout.nav.logout') }}</button>
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

        {{-- Modal de status global (showMessage disponível em todas as páginas) --}}
        @includeIf('components.status_modal')

        <footer class="text-center py-4 text-muted small">
            <p class="mb-1">dotProject+ | Uma Ferramenta Educacional para o Gerenciamento de Projetos</p>
            <a href="http://www.gqs.ufsc.br/evolution-of-dotproject/"
                target="_blank">www.gqs.ufsc.br/evolution-of-dotproject</a>
        </footer>

    </div>
@endsection