<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registro de Stakeholders</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10px;
        }
        .container { width: 95%; margin: 0 auto; }
        h1 { text-align: center; font-size: 16px; }
        h2 { font-size: 13px; font-weight: normal; margin-bottom: 20px; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #f4f4f4;
            font-size: 11px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Registro de Stakeholders</h1>
    <h2>Projeto: {{ $initiating->project->project_name ?? 'N/A' }}</h2>

    <table>
        <thead>
        <tr>
            <th>Stakeholder</th>
            <th>Responsabilidades</th>
            <th>Interesse</th>
            <th>Poder</th>
            <th>Estratégia</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($initiating->stakeholders as $stakeholder)
            <tr>
                <td>{{ $stakeholder->contact->full_name ?? 'N/A' }}</td>
                <td>{{ $stakeholder->stakeholder_responsibility }}</td>
                <td>{{ $stakeholder->stakeholder_interest }}</td>
                <td>{{ $stakeholder->stakeholder_power }}</td>
                <td>{{ $stakeholder->stakeholder_strategy }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align: center;">
                    Nenhum stakeholder cadastrado.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>
</body>
</html>
