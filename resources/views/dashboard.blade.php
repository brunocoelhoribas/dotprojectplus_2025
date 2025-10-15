<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>body { font-family: sans-serif; padding: 2rem; } form { display: inline; } button { background: none; border: none; color: blue; text-decoration: underline; cursor: pointer; padding: 0; }</style>
</head>
<body>
<h1>Bem-vindo, {{ Auth::user()->user_username }}!</h1>
<p>Você está logado.</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
</body>
</html>
