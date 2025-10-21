@extends('layouts.app')
@section('title', 'Login - dotProject+')

@section('content')
    <div class="container">
        <div class="row min-vh-100 d-flex align-items-center justify-content-center">
            <div class="col-md-5 col-lg-4">
                 <h1 class="h3 fw-bold text-center text-white">IFC</h1>
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">
                            <img src="{{ asset('images/dotproject_plus_logo.jpg') }}" alt="Logo do dotProject+" style="max-width: 200px;">
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="username" class="form-label">Nome do Usuário</label>
                                <input
                                    id="username"
                                    type="text"
                                    name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username') }}"
                                    required
                                    autofocus>

                                @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Senha</label>
                                <input
                                    id="password"
                                    type="password"
                                    class="form-control"
                                    name="password"
                                    required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark btn-lg">
                                    Login
                                </button>
                            </div>
                        </form>

                        <div class="text-center text-muted small mt-4">
                            <p class="mb-1"><a href="#" class="text-decoration-none">Socorro! Esqueci meu nome de usuário e senha?</a></p>
                            <p class="mb-1">versão 3.0</p>
                            <p class="mb-0">Cookies devem estar habilitados em seu navegador.</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
