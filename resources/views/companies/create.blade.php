@extends('dashboard')
@section('title', 'Nova Empresa')
@section('dashboard-content')
    <h1 class="h2 mb-4">Nova Empresa</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('companies.store') }}" method="POST">
                @csrf
                @include('companies._form')
            </form>
        </div>
    </div>
@endsection
