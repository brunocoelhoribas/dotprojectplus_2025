@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('projects/pdf.initiating.main_title') }}</title>
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
            text-transform: uppercase;
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
    <h1>{{ __('projects/pdf.initiating.main_title') }}</h1>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.project_title') }}</strong>
        <div class="field-content">{{ $initiating->initiating_title }}</div>
    </div>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.manager') }}</strong>
        <div class="field-content">{{ $manager->full_name ?? 'N/A' }}</div>
    </div>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.justification') }}</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_justification)) !!}</div>
    </div>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.objectives') }}</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_objective)) !!}</div>
    </div>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.expected_results') }}</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_expected_result)) !!}</div>
    </div>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.premises') }}</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_premise)) !!}</div>
    </div>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.restrictions') }}</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_restrictions)) !!}</div>
    </div>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.budget') }}</strong>
        <div class="field-content">{{ number_format($initiating->initiating_budget, 2, ',', '.') }}</div>
    </div>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.start_date') }}</strong>
        <div
            class="field-content">{{ $initiating->initiating_start_date ? Carbon::parse($initiating->initiating_start_date)->format('d/m/Y') : '-' }}</div>
    </div>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.end_date') }}</strong>
        <div
            class="field-content">{{ $initiating->initiating_end_date ? Carbon::parse($initiating->initiating_end_date)->format('d/m/Y') : '-' }}</div>
    </div>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.milestones') }}</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_milestone)) !!}</div>
    </div>

    <div class="field">
        <strong>{{ __('projects/pdf.initiating.success_criteria') }}</strong>
        <div class="field-content">{!! nl2br(e($initiating->initiating_success)) !!}</div>
    </div>

</div>
</body>
</html>
