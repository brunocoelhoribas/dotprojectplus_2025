<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Termo de Abertura do Projeto</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            line-height: 1.5;
        }
        .container {
            width: 95%;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }
        h2 {
            font-size: 14px;
            background-color: #f4f4f4;
            padding: 5px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field strong {
            display: block;
            font-size: 12px;
            color: #555;
        }
        .field-content {
            padding-left: 10px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>TERMO DE ABERTURA DO PROJETO</h1>

    <div class="field">
        <strong>Título do Projeto:</strong>
        <div class="field-content">{{ $initiating->initiating_title }}</div>
    </div>

    <div class="field">
        <strong>Gerente do Projeto:</strong>
        <div class="field-content">{{ $manager->full_name ?? 'N/A' }}</div>
    </div>

    <div class="field">
        <strong>Justificativa:</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_justification)) !!}</div>
    </div>

    <div class="field">
        <strong>Objetivos:</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_objective)) !!}</div>
    </div>

    <div class="field">
        <strong>Resultados Esperados:</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_expected_result)) !!}</div>
    </div>

    <div class="field">
        <strong>Premissas:</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_premise)) !!}</div>
    </div>

    <div class="field">
        <strong>Restrições:</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_restrictions)) !!}</div>
    </div>

    <div class="field">
        <strong>Orçamento (R$):</strong>
        <div class="field-content">{{ number_format($initiating->initiating_budget, 2, ',', '.') }}</div>
    </div>

    <div class="field">
        <strong>Data de Início:</strong>
        <div class="field-content">{{ $initiating->initiating_start_date ? \Carbon\Carbon::parse($initiating->initiating_start_date)->format('d/m/Y') : '-' }}</div>
    </div>

    <div class="field">
        <strong>Data de Encerramento:</strong>
        <div class="field-content">{{ $initiating->initiating_end_date ? \Carbon\Carbon::parse($initiating->initiating_end_date)->format('d/m/Y') : '-' }}</div>
    </div>

    <div class="field">
        <strong>Marcos:</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_milestone)) !!}</div>
    </div>

    <div class="field">
        <strong>Critérios de Aceite (Sucesso):</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_success)) !!}</div>
    </div>

</div>
</body>
</html>
